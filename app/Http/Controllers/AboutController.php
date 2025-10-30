<?php

namespace App\Http\Controllers;

use App\Models\About; // ⬅️ The fix for "Class 'About' not found"
use App\Http\Requests\AboutRequest; // Used for update method validation
use Illuminate\View\View; // Required for type-hinting the index and edit methods
use Illuminate\Http\RedirectResponse; // Required for type-hinting the update method

class AboutController extends Controller
{
    /**
     * Public: show about page (uses your existing blade view).
     */
    public function index(): View
    {
        // Get the first about record or fallback to a default model instance
        // All data will be sourced from the database if a record exists.
        $about = About::first();

        if (!$about) {
            $about = new About([
                'title' => 'POJ Music Club',
                'founded_year' => 2017,
                'members_count' => 2000,
                'events_per_year' => 120,
                'short_description' => 'POJ Music Club is a creative hub where musicians, learners, and fans connect. We host live sessions, workshops, and curated programs to nurture musical talent and build a vibrant community.',
                'mission' => 'Inspire musical excellence through accessible learning and performance opportunities.',
                'vision' => 'A thriving music ecosystem where everyone can create, perform, and grow.',
                // FIX: json_encode() is used here to convert the array to a string, 
                // which resolves the 'json_decode(): array given' error in the Blade view.
                'activities' => json_encode(['Live shows & jam nights','Workshops & masterclasses','Student showcases','Community projects & collaborations']),
                'hero_image' => null, // Add null for safe fallback
            ]);
        }

        // Using 'frontend.about' matching your directory structure (case sensitivity matters).
        return view('frontend.about', compact('about')); 
    }

    /**
     * Admin: show edit form (Kept as per your original code)
     */
    public function edit(): View
    {
        $about = About::first() ?? new About();
        return view('admin.about.edit', compact('about'));
    }

    /**
     * Admin: update or create (Kept as per your original code)
     */
    public function update(AboutRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Ensure activities is an array (even if empty) before persisting.
        if (!isset($data['activities'])) {
            $data['activities'] = [];
        }

        $about = About::first();

        if ($about) {
            $about->update($data);
        } else {
            $about = About::create($data);
        }

        return redirect()->route('admin.about.edit')->with('success', 'About updated successfully.');
    }
}
