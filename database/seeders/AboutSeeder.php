<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    public function run(): void
    {
        About::updateOrCreate([], [
            'title' => 'POJ Music Club',
            'founded_year' => 2017,
            'members_count' => 2000,
            'events_per_year' => 120,
            'short_description' => 'POJ Music Club is a creative hub where musicians, learners, and fans connect. We host live sessions, workshops, and curated programs to nurture musical talent and build a vibrant community.',
            'mission' => 'Inspire musical excellence through accessible learning and performance opportunities.',
            'vision' => 'A thriving music ecosystem where everyone can create, perform, and grow.',
            'activities' => ['Live shows & jam nights','Workshops & masterclasses','Student showcases','Community projects & collaborations'],
        ]);
    }
}