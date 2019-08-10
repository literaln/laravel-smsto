<?php

return [
    'client_id' => env('SMS_TO_CLIENT_ID', ''),

    'client_secret' => env('SMS_TO_CLIENT_SECRET', ''),

    'username' => env('SMS_TO_USERNAME', ''),

    'password' => env('SMS_TO_PASSWORD', ''),

    'base_url' => env('SMS_TO_BASE_URL', 'https://api.sms.to/v1'),

    'callback_url' => env('SMS_TO_CALLBACK_URL', '')
];