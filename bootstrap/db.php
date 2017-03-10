<?php

use Illuminate\Database\Capsule\Manager as Capsule;

return function ($c) {
    $capsule = new Capsule();
    $capsule->addConnection([
        'driver'    => getenv('DB_TYPE') ?: 'mysql',
        'host'      => getenv('DB_HOST') ?: '127.0.0.1',
        'database'  => getenv('DB_DATABASE') ?: 'weelnk',
        'username'  => getenv('DB_USERNAME') ?: 'root',
        'password'  => getenv('DB_PASSWORD') ?: '',
        'prefix'    => getenv('DB_PREFIX') ?: '',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ]);

    $capsule->setAsGlobal();
    return $capsule;
};
