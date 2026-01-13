<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateResumeRequest;
use App\Http\Requests\UpdateResumeRequest;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

function to_snake_case(array $arr): array
{
    return collect($arr)
        ->mapWithKeys(fn ($value, $key) => [Str::snake($key) => $value])
        ->toArray();
}

class ResumeController extends Controller
{
    public function getInitialData(Request $request)
    {
        return $request->user()->resumes->map(fn ($resume) => [
            'id' => $resume->id,
            'title' => $resume->title,
            'template' => $resume->template,
            'createdAt' => $resume->created_at,
        ]);
    }

    public function loadResume(Resume $resume)
    {
        if (! Gate::allows('view', $resume)) {
            abort(403);
        }

        return $resume->load(['personal_details', 'socials', 'experiences', 'educations', 'projects', 'certifications']);
    }

    public function createResume(CreateResumeRequest $request)
    {
        $resume_data = $request->validated();
        $user = $request->user();

        return DB::transaction(function () use ($user, $resume_data) {
            $core = $resume_data['core'];
            $personal_details = $resume_data['personalDetails'];
            $socials = $resume_data['socials'] ?? null;

            $experiences = $resume_data['experiences'] ?? [];
            $educations = $resume_data['educations'] ?? [];
            $projects = $resume_data['projects'] ?? [];
            $certifications = $resume_data['certifications'] ?? [];

            $resume = $user->resumes()->create(to_snake_case($core));
            $pd = $resume->personal_details()->create($personal_details);
            $sc = $socials != null ? $resume->socials()->create($socials) : null;
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

            return $resume->load(['personal_details', 'socials', 'experiences', 'educations', 'projects', 'certifications']);
        });
    }

    protected function syncHasMany($relation, array $items): void
    {
        $incomingIds = collect($items)
            ->pluck('id')
            ->filter()
            ->toArray();

        (clone $relation)->when(
            count($incomingIds) > 0,
            fn ($q) => $q->whereNotIn('id', $incomingIds),
        )->delete();

        foreach ($items as $item) {
            if (isset($item['id'])) {
                (clone $relation)
                    ->where('id', $item['id'])
                    ->update(
                        to_snake_case($item)
                    );
            } else {
                (clone $relation)->create(
                    to_snake_case($item)
                );
            }
        }
    }

    public function updateResume(UpdateResumeRequest $request, Resume $resume)
    {
        $resume_data = $request->validated();

        if (! Gate::allows('update', $resume)) {
            abort(403);
        }

        return DB::transaction(function () use ($resume, $resume_data) {
            $core = $resume_data['core'];
            $personal_details = $resume_data['personalDetails'];

            $resume->update(to_snake_case($core));
            $resume->personal_details()->update($personal_details);

            if (array_key_exists('socials', $resume_data) && $resume_data['socials'] !== null) {
                $resume->socials()->updateOrCreate(
                    [],
                    $resume_data['socials']
                );
            }

            if (array_key_exists('experiences', $resume_data)) {
                $this->syncHasMany(
                    $resume->experiences(),
                    $resume_data['experiences']
                );
            } else {
                $resume->experiences()->delete();
            }

            if (array_key_exists('educations', $resume_data)) {
                $this->syncHasMany(
                    $resume->educations(),
                    $resume_data['educations']
                );
            } else {
                $resume->educations()->delete();
            }

            if (array_key_exists('projects', $resume_data)) {
                $this->syncHasMany(
                    $resume->projects(),
                    $resume_data['projects']
                );
            } else {
                $resume->projects()->delete();
            }

            if (array_key_exists('certifications', $resume_data)) {
                $this->syncHasMany(
                    $resume->certifications(),
                    $resume_data['certifications']
                );
            } else {
                $resume->certifications()->delete();
            }

            return $resume->load(['personal_details', 'socials', 'experiences', 'educations', 'projects', 'certifications']);
        });
    }
}
