<?php

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // ── Tambahkan hanya SATU blok ini ──
    'gemini' => [
        'api_key'          => env('GEMINI_API_KEY'),
        'model'            => env('GEMINI_MODEL', 'gemini-2.0-flash'),
        'base_url'         => 'https://generativelanguage.googleapis.com/v1beta/models/',
        'daily_free_limit' => env('AI_DAILY_FREE_LIMIT', 5),
    ],

];