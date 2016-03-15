<?php

return [
    'client_id'    => env('GOOGLE_CLIENT_ID', ''),
    'client_secret'    => env('GOOGLE_CLIENT_SECRET', ''),
    'callback_url' => url("auth/callback/drive")
];
