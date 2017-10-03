<?php

require __DIR__.'/../bootstrap/autoload.php';

try {
    // Attempt to load .env and do nothing if it fails
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| GO!
|--------------------------------------------------------------------------
*/

$app = require __DIR__.'/../bootstrap/app.php';
$app->run();
