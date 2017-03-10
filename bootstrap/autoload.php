<?php

require __DIR__.'/../vendor/autoload.php';

try {
    // Attempt to load .env and do nothing if it fails
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {}
