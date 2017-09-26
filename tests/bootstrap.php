<?php

require __DIR__.'/../bootstrap/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$connection = $app->getContainer()->get('db');

$configuration = new Doctrine\DBAL\Migrations\Configuration\Configuration($connection);
$configuration->setMigrationsDirectory(__DIR__.'/../migrations');
$configuration->setMigrationsNamespace('Migrations');

$migration = new Doctrine\DBAL\Migrations\Migration($configuration);

$path = __DIR__.'/../weelnk-test.sqlite';
if (file_exists($path)) {
    unlink($path);
}

$migration->migrate();

// Seed test data

$urls = [
    'https://github.com/davedevelopment/phpmig/commit/e24b303936931c9b912f13ad0a3ea8351efa8f00',
    'https://www.google.ca/search?site=&source=hp&q=help&oq=help&gs_l=hp.3..0l10.841.1133.0.1269.5.5.0.0.0.0.109.274.1j2.3.0....0...1.1.64.hp..2.2.209.0..35i39k1j0i131k1.QaYVJlhDtOA',
    'https://www.google.ca/maps/place/Toronto,+ON/@43.7181557,-79.5181416,11z/data=!3m1!4b1!4m5!3m4!1s0x89d4cb90d7c63ba5:0x323555502ab4c477!8m2!3d43.653226!4d-79.3831843?hl=en',
    'https://google.ca'
];

foreach($urls as $url) {
    $connection->createQueryBuilder()
        ->insert('links')
        ->values([
            'md5' => '?',
            'url' => '?',
        ])
        ->setParameter(0, md5($url))
        ->setParameter(1, $url)
        ->execute();
}
