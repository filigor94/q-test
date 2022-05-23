<?php

return [
    'url_access_token' => env('CLIENT_URL_ACCESS_TOKEN', '/api/v2/token'),
    'url_refresh_token' => env('CLIENT_URL_REFRESH_TOKEN', '/api/v2/token/refresh'),
];
