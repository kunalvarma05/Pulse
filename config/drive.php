<?php

return [
    'client_id'    => env('GOOGLE_CLIENT_ID', ''),
    'client_secret'    => env('GOOGLE_CLIENT_SECRET', ''),
    'callback_url' => env('GOOGLE_CALLBACK_URL'),
    'scopes' => [Google_Service_Drive::DRIVE, 'profile', 'email']
];
