<?php

namespace App\Http\Requests;

use App\ResumeTemplateEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateResumeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() != null;
    }

    public function rules(): array
    {

        $MAX_EXPERIENCES = 3;
        $MAX_PROJECTS = 2;
        $MAX_PROJECT_TECHNOLOGIES = 5;
        $MAX_EDUCATIONS = 2;
        $MAX_SKILLS = 8;
        $MAX_CERTIFICATIONS = 2;
        $MAX_LANGUAGES = 5;

        return [
            'core.title' => ['required', 'min:2', 'max:255'],
            'core.summary' => ['nullable', 'max:255'],
            'core.accentColor' => ['required', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'core.template' => ['nullable', Rule::enum(ResumeTemplateEnum::class)],
            'core.skills' => ['max:'.$MAX_SKILLS],
            'core.languages' => ['max:'.$MAX_LANGUAGES],

            'personalDetails.fullName' => ['required', 'string', 'min:1'],
            'personalDetails.designation' => ['required', 'string', 'min:1'],
            'personalDetails.email' => ['required', 'email'],
            'personalDetails.phone' => ['nullable', 'regex:/^\+?[1-9]\d{1,14}$/'],
            'personalDetails.location' => ['nullable', 'string'],

            'socials.linkedIn' => ['nullable', 'url', 'regex:/^https:\/\/(www\.)?linkedin\.com\/in\/[A-Za-z0-9_-]+\/?$/'],
            'socials.github' => ['nullable', 'url', 'regex:/^https:\/\/(www\.)?github\.com\/[A-Za-z0-9_-]+\/?$/'],
            'socials.portfolio' => ['nullable', 'url'],

            'experiences' => ['array', 'max:'.$MAX_EXPERIENCES],
            'experiences.*.organization' => ['required', 'string', 'min:1'],
            'experiences.*.description' => ['required', 'string', 'min:1'],
            'experiences.*.position' => ['required', 'string', 'min:1'],
            'experiences.*.startDate' => ['required', 'date'],
            'experiences.*.endDate' => ['nullable', 'date'],

            'educations' => ['array', 'max:'.$MAX_EDUCATIONS],
            'educations.*.institution' => ['required', 'string', 'min:1'],
            'educations.*.degree' => ['required', 'string', 'min:1'],
            'educations.*.field' => ['nullable', 'string'],
            'educations.*.startDate' => ['required', 'date'],
            'educations.*.endDate' => ['nullable', 'date'],
            'educations.*.grade' => ['nullable', 'decimal:1'],

            'projects' => ['array', 'max:'.$MAX_PROJECTS],
            'projects.*.name' => ['required', 'string', 'min:1'],
            'projects.*.description' => ['required', 'string', 'min:1'],
            'projects.*.technologies' => [
                'required',
                'array',
                'min:1',
                'max:'.$MAX_PROJECT_TECHNOLOGIES,
            ],
            'projects.*.technologies.*' => ['required', 'string'],
            'projects.*.liveLink' => ['nullable', 'url'],
            'projects.*.githubLink' => [
                'nullable',
                'url',
                'regex:/^https:\/\/(www\.)?github\.com\/[A-Za-z0-9_-]+\/[A-Za-z0-9_-]+(\/.*)?$/',
            ],
            'projects.*.startDate' => ['required', 'date'],
            'projects.*.endDate' => ['nullable', 'date'],

            'certifications' => ['array', 'max:'.$MAX_CERTIFICATIONS],
            'certifications.*.title' => ['required', 'string', 'min:1'],
            'certifications.*.issuer' => ['required', 'string', 'min:1'],
            'certifications.*.date' => ['required', 'date'],
            'certifications.*.url' => ['required', 'url'],
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'title.required' => 'Title is required',
    //     ];
    // }
}
