<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateResumeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

function to_snake_case(array $arr): array
{
    return collect($arr)
        ->mapWithKeys(fn ($value, $key) => [Str::snake($key) => $value])
        ->toArray();
}

class ResumeController extends Controller
{
    public function getAll(Request $request)
    {
        return $request->user()
            ->resumes()
            ->with(['personal_details', 'socials', 'experiences',
                'educations', 'projects', 'certifications'])
            ->get();
    }

    public function create(CreateResumeRequest $request)
    {
        $resume_data = $request->validated();
        $user = $request->user();

        return DB::transaction(function () use ($user, $resume_data) {
            $core = $resume_data['core'];
            $personal_details = $resume_data['personalDetails'];
            $socials = $resume_data['socials'];

            $experiences = $resume_data['experiences'] ?? [];
            $educations = $resume_data['educations'] ?? [];
            $projects = $resume_data['projects'] ?? [];
            $certifications = $resume_data['certifications'] ?? [];

            $resume = $user->resumes()->create(to_snake_case($core));
            $pd = $resume->personal_details()->create($personal_details);
            $sc = $resume->socials()->create($socials);
            $exps = count($experiences) > 0 ? $resume->experiences()->createMany(
                collect($experiences)
                    ->map(fn ($value) => to_snake_case($value))
                    ->toArray()
            ) : [];
            $eds = count($educations) > 0 ? $resume->educations()->createMany(
                collect($educations)
                    ->map(fn ($value) => to_snake_case($value))
                    ->toArray()
            ) : [];
            $projs = count($projects) > 0 ? $resume->projects()->createMany(
                collect($projects)
                    ->map(fn ($value) => to_snake_case($value))
                    ->toArray()
            ) : [];
            $certs = $resume->certifications()->createMany($certifications);

            return [$resume,
                'personalDetails' => $pd,
                'socials' => $sc,
                'experiences' => $exps,
                'educations' => $eds,
                'projects' => $projs,
                'certifications' => $certs];
        });
    }
}
