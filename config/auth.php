<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    | Supported: "session"
    */

    'guards' => [
        // 🔹 Default guard for admin/users
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // 🔹 Member panel/login এর জন্য আলাদা guard
        'member' => [
            'driver' => 'session',
            'provider' => 'members',
        ],
'subscriber' => ['driver' => 'session', 'provider' => 'subscribers'], 

    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    | Supported: "database", "eloquent"
    */

    'providers' => [
        // 🔹 Admin/User Provider
        'users' => [
            'driver' => 'eloquent',
            'model'  => env('AUTH_MODEL', App\Models\User::class),
        ],

        // 🔹 Member Provider (Modules path অনুযায়ী আপডেটেড)
        'members' => [
            'driver' => 'eloquent',
            'model'  => Modules\Members\Models\Member::class,
        ],

        'subscribers' => ['driver' => 'eloquent', 'model' => Modules\Subscribers\Models\Subscriber::class], // ✅ new

    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        // 🔹 Admin/User Password Broker
        'users' => [
            'provider' => 'users',
            'table'    => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire'   => 60,
            'throttle' => 60,
        ],

        // 🔹 Member Password Broker
        'members' => [
            'provider' => 'members',
            'table'    => env('AUTH_MEMBER_PASSWORD_RESET_TOKEN_TABLE', 'member_password_reset_tokens'),
            'expire'   => 60,
            'throttle' => 60,
        ],
'subscribers' => [
        'provider' => 'subscribers', 'table' => 'password_reset_tokens',
        'expire' => 60, 'throttle' => 60,
    ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
