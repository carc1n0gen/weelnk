<?php

use Illuminate\Cache\FileStore;
use Illuminate\Cache\RedisStore;

return function ($c) {
    $driver = getenv('CACHE_DRIVER') ?: 'file';
    switch ($driver) {
        case 'file':
            $cache = new FileStore($c->get('fileSystem'), __DIR__.'/../storage/cache/links');
            break;

        case 'redis':
            $cache = new RedisStore($c->get('redis'), 'weelnk');
            break;

        default:
            throw new \Exception("Unsupported cache driver \"$driver\"");
    }

    return $cache;
};
