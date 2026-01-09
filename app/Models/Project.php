<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'technologies',
        'liveLink',
        'githubLink',
        'startDate',
        'endDate',
        'resumeId',
    ];

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
        ];
    }

    protected $attributes = ['technologies' => '[]'];
}
