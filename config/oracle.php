<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
        // 'tns'            => env('ORCL_DB_TNS', ''),
        'host'           => env('ORCL_DB_HOST', ''),
        'port'           => env('ORCL_DB_PORT', '1521'),
        'database'       => env('ORCL_DB_DATABASE', ''),
        'username'       => env('ORCL_DB_USERNAME', ''),
        'password'       => env('ORCL_DB_PASSWORD', ''),
        'charset'        => env('ORCL_DB_CHARSET', 'AL32UTF8'),
        'prefix'         => env('ORCL_DB_PREFIX', ''),
        'prefix_schema'  => env('ORCL_DB_SCHEMA_PREFIX', ''),
        'edition'        => env('ORCL_DB_EDITION', 'ora$base'),
        'server_version' => env('ORCL_DB_SERVER_VERSION', '11g'),
    ],
];
