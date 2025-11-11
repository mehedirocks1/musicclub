<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backup Name
    |--------------------------------------------------------------------------
    */
    'backup' => [
        'name' => env('APP_NAME', 'laravel-backup'),

        /*
        |--------------------------------------------------------------------------
        | Source: Files and Databases
        |--------------------------------------------------------------------------
        */
        'source' => [
            'files' => [
                'include' => [
                    base_path('app'),
                    base_path('Modules'),
                    public_path('uploads'),
                    storage_path('app'),
                ],

                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                ],

                'follow_links' => false,
                'ignore_unreadable_directories' => true,
                'relative_path' => null,
            ],

            'databases' => [
                env('DB_CONNECTION', 'mysql'),
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Database dump settings
        |--------------------------------------------------------------------------
        */
        // Disable gzip compressor for Windows
        'database_dump_compressor' => null,
        'database_dump_file_timestamp_format' => 'Y-m-d_H-i-s',
        'database_dump_filename_base' => 'database',
        'database_dump_file_extension' => 'sql',

        /*
        |--------------------------------------------------------------------------
        | Backup Destination
        |--------------------------------------------------------------------------
        */
        'destination' => [
            'filename_prefix' => '',
            'compression_method' => null, // no zip compression for simplicity
            'disks' => [
                env('BACKUP_DISK', 'backups'),
            ],
        ],

        'temporary_directory' => storage_path('app/backup-temp'),
        'password' => null,
        'encryption' => 'default',
        'tries' => 1,
        'retry_delay' => 0,
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        // Disable notifications for Windows/local
        'notifications' => [],
        
        'notifiable' => \Spatie\Backup\Notifications\Notifiable::class,

        'mail' => [
            'to' => env('BACKUP_NOTIFICATION_EMAIL', 'admin@example.com'),
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', 'Your App'),
            ],
        ],

        'slack' => [
            'webhook_url' => env('BACKUP_SLACK_WEBHOOK_URL', ''),
            'channel' => null,
            'username' => null,
            'icon' => null,
        ],

        'discord' => [
            'webhook_url' => env('BACKUP_DISCORD_WEBHOOK_URL', ''),
            'username' => '',
            'avatar_url' => '',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitor backups health
    |--------------------------------------------------------------------------
    */
    'monitor_backups' => [
        [
            'name' => env('APP_NAME', 'laravel-backup'),
            'disks' => [env('BACKUP_DISK', 'backups')],
            'health_checks' => [
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumAgeInDays::class => 1,
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes::class => 5000,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cleanup Strategy
    |--------------------------------------------------------------------------
    */
    'cleanup' => [
        'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,

        'default_strategy' => [
            'keep_all_backups_for_days' => 7,
            'keep_daily_backups_for_days' => 16,
            'keep_weekly_backups_for_weeks' => 8,
            'keep_monthly_backups_for_months' => 12,
            'keep_yearly_backups_for_years' => 2,
            'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
        ],

        'tries' => 1,
        'retry_delay' => 0,
    ],

];
