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
        'accent_color',
        'template',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function personal_details()
    {
        return $this->hasOne(PersonalDetails::class);
    }

    public function socials()
    {
        return $this->hasOne(Socials::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }
}
