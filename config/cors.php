<?php

return [

    'paths' => ['api/*', 'http://localhost:8000'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // or ['http://localhost:3000'] for frontend

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
