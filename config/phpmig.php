<?php

$app = require __DIR__.'/../bootstrap/app.php';
$db = $app->getContainer()->get('db');

$container = new ArrayObject();
//$container['phpmig.adapter'] = new \Phpmig\Adapter\Illuminate\Database($db, 'migrations'); Get a doctrine adapter
$container['phpmig.migrations_path'] = __DIR__.'/../migrations';
$container['phpmig.migrations_template_path'] = __DIR__.'/../migrations/.template.php';

return $container;
