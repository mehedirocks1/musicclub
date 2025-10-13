<?php

namespace Modules\Members\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Members\Models\Member::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
 'profile_pic' => null,
            'member_id' => (string) fake()->unique()->numberBetween(1000, 999999),
            'username' => fake()->unique()->userName(),
            'name_bn' => null,
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '01' . fake()->numberBetween(3,9) . fake()->numerify('########'),
            'father_name' => fake()->name('male'),
            'mother_name' => fake()->name('female'),
            'dob' => fake()->date(),
            'id_number' => (string) fake()->numerify('############'),
            'gender' => fake()->randomElement(Member::GENDERS),
            'blood_group' => fake()->randomElement(Member::BLOOD_GROUPS),
            'education_qualification' => 'HSC',
            'profession' => 'Student',
            'other_expertise' => null,
            'country' => 'Bangladesh',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'address' => fake()->address(),
            'membership_type' => fake()->randomElement(Member::MEMBERSHIP_TYPES),
            'registration_date' => now()->toDateString(),
            'balance' => 0,



        ];
    }
}

