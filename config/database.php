<?php

return [

    'sqlite' => [

        'driver' => 'sqlite',

        'database' => __DIR__.'/../storage/database/weelnk.sqlite',

        'prefix' => getenv('DB_PREFIX') ?: '',
    ],
    
    'mysql' => [

        'driver' => 'mysql',

        'host' => getenv('DB_HOST') ?: '127.0.0.1',

        'port' => getenv('DB_PORT') ?: '3306',

        'database' => getenv('DB_DATABASE') ?: 'weelnk',

        'username' => getenv('DB_USERNAME') ?: 'root',

        'password' => getenv('DB_PASSWORD') ?: '',

        'prefix' => getenv('DB_PREFIX') ?: '',

        'charset' => 'utf8mb4',

        'collation' => 'utf8mb4_unicode_ci',

        'strict' => true,

        'engine' => null,

    ],

    'pgsql' => [

        'driver' => 'pgsql',

        'host' => getenv('DB_HOST') ?: '127.0.0.1',

        'port' => getenv('DB_PORT') ?: '5432',

        'database' => getenv('DB_DATABASE') ?: 'weelnk',

        'username' => getenv('DB_USERNAME') ?: 'root',

        'password' => getenv('DB_PASSWORD') ?: '',

        'prefix' => getenv('DB_PREFIX') ?: '',

        'charset' => 'utf8',

        'schema' => 'public',
        
        'sslmode' => 'prefer',
    ],

];