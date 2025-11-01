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
        $membershipStatus = $this->faker->randomElement(Member::MEMBERSHIP_STATUS);

        // Determine membership start and expiry based on status and plan
        $plan = $this->faker->randomElement(Member::MEMBERSHIP_PLANS);
        $startDate = now();
        $expiresDate = $plan === 'monthly' ? $startDate->copy()->addMonth() : $startDate->copy()->addYear();

        // If membership is pending or inactive, expire accordingly
        if ($membershipStatus !== 'active') {
            $startDate = null;
            $expiresDate = null;
        }

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
            'password' => Hash::make('password'), // default hashed password

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
            'membership_plan' => $plan,
            'membership_status' => $membershipStatus,
            'membership_started_at' => $startDate ? $startDate->toDateString() : null,
            'membership_expires_at' => $expiresDate ? $expiresDate->toDateString() : null,
            'registration_date' => now()->toDateString(),

            // 💰 Account
            'balance' => 0.00,

            // 💳 Last payment snapshot
            'last_payment_amount' => $membershipStatus === 'active' ? $this->faker->randomFloat(2, 100, 1000) : null,
            'last_payment_tran_id' => $membershipStatus === 'active' ? 'TRX' . $this->faker->unique()->numberBetween(10000, 999999) : null,
            'last_payment_at' => $membershipStatus === 'active' ? now() : null,
            'last_payment_gateway' => $membershipStatus === 'active' ? $this->faker->randomElement(['sslcommerz','paypal','stripe']) : null,

            // 🔘 Status
            'status' => $membershipStatus === 'active' ? 'active' : 'inactive',
        ];
    }
}
