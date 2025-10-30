<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $table = 'abouts';

    protected $fillable = [
        'title',
        'founded_year',
        'members_count',
        'events_per_year',
        'short_description',
        'mission',
        'vision',
        'activities', // stored as JSON
        'hero_image', // optional image path
    ];

    protected $casts = [
        'activities' => 'array',
    ];
}