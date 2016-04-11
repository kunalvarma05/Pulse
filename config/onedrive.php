<?php

return [
'client_id'          => env('MICROSOFT_CLIENT_ID'),
'client_secret'      => env('MICROSOFT_CLIENT_SECRET'),
'callback_url'       => env('MICROSOFT_CALLBACK_URL'),
'scope'              => ['wl.basic', 'wl.signin', 'wl.offline_access', 'onedrive.readwrite']
];