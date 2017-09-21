<?php

/*
|--------------------------------------------------------------------------
| Initialize the slim app
|--------------------------------------------------------------------------
*/

$config = [
    'settings' => [
        'displayErrorDetails' => getenv('APP_DEBUG') ?: false,
    ],
];

$app = new Slim\App($config);

/*
|--------------------------------------------------------------------------
| Setup the dependencies in the DI container
|--------------------------------------------------------------------------
*/

$container = $app->getContainer();

$container['request'] = function ($c) {
    return App\Request::createFromEnvironment($c->get('environment'));
};

$container['response'] = function ($c) {
    $headers = new Slim\Http\Headers(['Content-Type' => 'text/html; charset=UTF-8']);
    $response = new App\Response(200, $headers);
    return $response->withProtocolVersion($c->get('settings')['httpVersion']);
};

$container['errorHandler'] = function ($c) {
    return new App\Handlers\ErrorHandler($c);
};

$container['db'] = function ($c) {
    $drivers = [
        'sqlite' => 'pdo_sqlite',
        'mysql' => 'pdo_mysql',
        'pgsql' => 'pdo_pgsql',
    ];
    $ports = [
        'mysql' => 3306,
        'pgsql' => 5432,
    ];

    $config = new \Doctrine\DBAL\Configuration();

    $type = getenv('DB_TYPE') ?: 'mysql';
    $connectionParams = array(
        'dbname' => getenv('DB_DATABASE') ?: 'weelnk',
        'user' => getenv('DB_USERNAME') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: $ports[$type],
        'driver' => $drivers[$type],
    );

    return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
};

$container['LinkStore'] = function ($c) {
    return new App\Stores\LinkStore($c);
};

$container['view'] = function ($c) {
    return new \Slim\Views\PhpRenderer(__DIR__.'/../app/views/');
};

// $container['redis'] = function ($c) {
//     return new Illuminate\Redis\RedisManager(
//         'predis',
//         [
//             'default' => [
//                 'scheme' => 'tcp',
//                 'host' => getenv('REDIS_HOST') ?: '127.0.0.1',
//                 'port' => getenv('REDIS_PORT') ?: 6379,
//             ],
//             'options' => [
//                 'parameters' => [
//                     'password' => getenv('REDIS_PASSWORD') ?: null,
//                 ],
//             ],
//         ]
//     );
// };

// $container['cache'] = function ($c) {
//     $driver = getenv('CACHE_DRIVER') ?: 'file';
//     switch ($driver) {
//         case 'array':
//             $cache = new Illuminate\Cache\ArrayStore();
//             break;

//         case 'file':
//             $cache = new Illuminate\Cache\FileStore($c->get('fileSystem'), __DIR__.'/../storage/cache/links');
//             break;

//         case 'redis':
//             $cache = new Illuminate\Cache\RedisStore($c->get('redis'), 'weelnk');
//             break;

//         default:
//             throw new \Exception("Unsupported cache driver \"$driver\"");
//     }

//     return new Illuminate\Cache\Repository($cache);
// };

$container['logger'] = function ($c) {
    $logger = new Monolog\Logger('weelnk');
    $file_handler = new Monolog\Handler\StreamHandler(
        __DIR__.'/../storage/logs/weelnk.log',
        Monolog\Logger::toMonologLevel(getenv('APP_LOG_LEVEL') ?: 'info')
    );
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['shortlink'] = function ($c) {
    return new Carc1n0gen\ShortLink\Converter('abcdefghijklmnopqrswpwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
};

// $container['fileSystem'] = function ($c) {
//     return new Illuminate\Filesystem\Filesystem();
// };

$app->add(App\Middleware\RequestLogger::class);

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

$app->get('/', function ($request, $response) {
    return $this->view->render($response, 'form.php');
});

$app->get('/{shortLink}', App\Handlers\LinkFetchHandler::class);

$app->post('/', App\Handlers\LinkShortenHandler::class);

return $app;
