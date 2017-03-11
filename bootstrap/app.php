<?php

$config = [
    'settings' => [
        'displayErrorDetails' => getenv('APP_DEBUG') ?: false,
        'database' => require __DIR__.'/../config/database.php',
    ],
];

$app = new Slim\App($config);

$container = $app->getContainer();
$container['request'] = require __DIR__.'/request.php';
$container['response'] = require __DIR__.'/response.php';
$container['errorHandler'] = require __DIR__.'/errorHandler.php';

$container['db'] = require __DIR__.'/db.php';
$container['view'] = require __DIR__.'/view.php';
$container['redis'] = require __DIR__.'/redis.php';
$container['cache'] = require __DIR__.'/cache.php';
$container['logger'] = require __DIR__.'/logger.php';
$container['shortlink'] = require __DIR__.'/shortlink.php';
$container['fileSystem'] = require __DIR__.'/fileSystem.php';

$app->add(App\Http\Middleware\RequestLogger::class);

require __DIR__.'/../app/Http/routes.php';
return $app;
