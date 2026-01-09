<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'summary',
        'skills',
        'languages',
        'accentColor',
        'template',
        'userId',
    ];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'languages' => 'array',
        ];
    }

    protected $attributes = [
        'skills' => '[]',
        'languages' => '[]',
    ];

    public function personal_details()
    {
        return $this->hasOne(PersonalDetails::class, 'resumeId', 'id');
    }

    public function socials()
    {
        return $this->hasOne(Socials::class, 'resumeId', 'id');
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class, 'resumeId', 'id');
    }

    public function educations()
    {
        return $this->hasMany(Education::class, 'resumeId', 'id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'resumeId', 'id');
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class, 'resumeId', 'id');
    }
}
