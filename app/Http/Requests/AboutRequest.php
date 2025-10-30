<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Return true only if admin/authenticated; adjust as needed
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'founded_year' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'members_count' => 'nullable|integer|min:0',
            'events_per_year' => 'nullable|integer|min:0',
            'short_description' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'activities' => 'nullable|array',
            'activities.*' => 'string|max:255',
            'hero_image' => 'nullable|string|max:1024',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Ensure activities submitted as comma-separated string become array (optional)
        if (is_string($this->activities)) {
            $this->merge([
                'activities' => array_values(array_filter(array_map('trim', explode(',', $this->activities)))),
            ]);
        }
    }
}