<?php

namespace Modules\Members\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\Members\Models\Member;

class MemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Member::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'profile_pic' => null,

            // 🆔 Identity
            'member_id' => 'M' . $this->faker->unique()->numberBetween(1000, 999999),
            'username' => $this->faker->unique()->userName(),
            'name_bn' => null,
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => '01' . $this->faker->numberBetween(3, 9) . $this->faker->numerify('########'),

            // 🔐 Authentication
            'password' => Hash::make('password'), // ✅ ডিফল্ট hashed password

            // 👨‍👩 Family Info
            'father_name' => $this->faker->name('male'),
            'mother_name' => $this->faker->name('female'),

            // 🎂 Personal Info
            'dob' => $this->faker->date(),
            'id_number' => (string) $this->faker->numerify('############'),
            'gender' => $this->faker->randomElement(Member::GENDERS),
            'blood_group' => $this->faker->randomElement(Member::BLOOD_GROUPS),

            // 🎓 Professional Info
            'education_qualification' => 'HSC',
            'profession' => 'Student',
            'other_expertise' => null,

            // 🌍 Address
            'country' => 'Bangladesh',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'address' => $this->faker->address(),

            // 🧾 Membership
            'membership_type' => $this->faker->randomElement(Member::MEMBERSHIP_TYPES),
            'registration_date' => now()->toDateString(),

            // 💰 Account
            'balance' => 0.00,
        ];
    }
}
