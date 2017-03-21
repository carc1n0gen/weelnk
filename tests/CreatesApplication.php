<?php

namespace Tests;

trait CreatesApplication
{
    public static function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';
        return $app;
    }
}