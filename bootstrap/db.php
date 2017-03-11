<?php

use Illuminate\Database\Capsule\Manager as Capsule;

return function ($c) {
    $config = $c->get('settings')['database'];

    $capsule = new Capsule();
    $capsule->addConnection($config[getenv('DB_TYPE') ?: 'mysql']);
    $capsule->setAsGlobal();
    
    return $capsule;
};
