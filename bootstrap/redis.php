<?php

use Illuminate\Redis\RedisManager;

return function ($c) {
    return new RedisManager(
        'predis',
        [
            'default' => [
                'scheme' => 'tcp',
                'host' => getenv('REDIS_HOST') ?: '127.0.0.1',
                'port' => getenv('REDIS_PORT') ?: 6379,
            ],
            'options' => [
                'parameters' => [
                    'password' => getenv('REDIS_PASSWORD') ?: null,
                ],
            ],
        ]
    );
};
