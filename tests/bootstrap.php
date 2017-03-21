<?php

require __DIR__.'/../bootstrap/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$output = new \Symfony\Component\Console\Output\NullOutput();

$container = require __DIR__.'/../config/phpmig.php';

$phpmig = new \Phpmig\Api\PhpmigApplication($container, $output);

$phpmig->down();
$phpmig->up();

$urls = [
    'https://github.com/davedevelopment/phpmig/commit/e24b303936931c9b912f13ad0a3ea8351efa8f00',
    'https://www.google.ca/search?site=&source=hp&q=help&oq=help&gs_l=hp.3..0l10.841.1133.0.1269.5.5.0.0.0.0.109.274.1j2.3.0....0...1.1.64.hp..2.2.209.0..35i39k1j0i131k1.QaYVJlhDtOA',
    'https://www.google.ca/maps/place/Toronto,+ON/@43.7181557,-79.5181416,11z/data=!3m1!4b1!4m5!3m4!1s0x89d4cb90d7c63ba5:0x323555502ab4c477!8m2!3d43.653226!4d-79.3831843?hl=en',
];

Capsule::table('links')->insert([
    [
        'url' => $urls[0],
        'md5' => md5($urls[0]),
    ],
    [
        'url' => $urls[1],
        'md5' => md5($urls[1]),
    ],
    [
        'url' => $urls[2],
        'md5' => md5($urls[2]),
    ],
]);
