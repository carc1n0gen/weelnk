<?php

require __DIR__.'/bootstrap/autoload.php';

try {
    // Attempt to load .env and do nothing if it fails
    (new Dotenv\Dotenv(__DIR__.'/'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Tools\Console\ConsoleRunner;

$app = require __DIR__.'/bootstrap/app.php';

$connection = $app->getContainer()->get(Connection::class);
return ConsoleRunner::createHelperSet($connection);
