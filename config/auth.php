<?php

return [
    /*
    |---------------------------------------------------------------------------
    | Authentication Defaults
    |---------------------------------------------------------------------------
    */
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'), // Default guard (can be 'web' or 'admin')
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |---------------------------------------------------------------------------
    | Authentication Guards
    |---------------------------------------------------------------------------
    */
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admin_users',
        ],
    ],

    /*
    |---------------------------------------------------------------------------
    | User Providers
    |---------------------------------------------------------------------------
    */
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'admin_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\AdminUser::class,
        ],
    ],

    /*
    |---------------------------------------------------------------------------
    | Resetting Passwords
    |---------------------------------------------------------------------------
    */
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        // Admin Password Reset
        'admin_users' => [ // Changed from 'admins' to 'admin_users'
            'provider' => 'admin_users',
            'table' => 'admin_password_reset_tokens', // Ensure this table exists
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |---------------------------------------------------------------------------
    | Password Confirmation Timeout
    |---------------------------------------------------------------------------
    */
    'password_timeout' => 10800, // 3 hours in seconds
];
