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

$container['db'] = function ($c) {
    $type = getenv('DB_TYPE') ?: 'mysql';
    $username = getenv('DB_USERNAME') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: 'root';
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $database = $type === 'sqlite'
        ? getenv('DB_DATABASE') ?: __DIR__.'/../storage/database/weelnk.sqlite'
        : getenv('DB_DATABASE') ?: 'weelnk';

    $config = new \Doctrine\DBAL\Configuration();
    $connectionParams = [
        'url' => "$type://$username:$password@$host/$database"
    ];
    return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
};

$container['LinkStore'] = function ($c) {
    return new App\Stores\LinkStore(
        $c['shortlink'],
        $c['db'],
        $c['cache']
    );
};

$container['view'] = function ($c) {
    return new \Slim\Views\PhpRenderer(__DIR__.'/../app/views/');
};

$container['fileSystem'] = function ($c) {
    $adapter = new League\Flysystem\Adapter\Local(__DIR__.'/../storage');
    return new League\Flysystem\Filesystem($adapter);
};

$container['cache'] = function ($c) {
    $driver = getenv('CACHE_DRIVER') ?: 'file';
    switch($driver) {
        case 'array':
            $pool = new Cache\Adapter\PHPArray\ArrayCachePool();
            break;

        case 'file':
            $pool = new Cache\Adapter\Filesystem\FilesystemCachePool($c['fileSystem']);
            $pool->setFolder('cache');
            break;

        case 'redis':
            $host = getenv('REDIS_HOST') ?: '127.0.0.1';
            $port = getenv('REDIS_PORT') ?: 6379;
            $client = new \Predis\Client("tcp:/$host:$port");
            $pool = new \Cache\Adapter\Predis\PredisCachePool($client);
            break;

        case 'memcached':
            $host = getenv('MEMCACHED_HOST') ?: '127.0.0.1';
            $port = getenv('MEMCACHED_PORT') ?: 11211;
            $client = new \Memcached();
            $client->addServer($host, $port);
            $pool = new \Cache\Adapter\Memcached\MemcachedCachePool($client);
            break;

        default:
            throw new \Exception("Unsupported cache driver \"$driver\"");
    }

    return $pool;
};

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
    return new Carc1n0gen\ShortLink\Converter(
        'abcdefghijklmnopqrswpwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );
};

/*
|--------------------------------------------------------------------------
| Controllers\Handlers
|--------------------------------------------------------------------------
*/

$container['errorHandler'] = function ($c) {
    return new App\Handlers\ErrorHandler(
        $c['view'],
        $c['logger']
    );
};

$container[App\Handlers\LinkShortenHandler::class] = function ($c) {
    return new App\Handlers\LinkShortenHandler(
        $c['LinkStore'],
        $c['view']
    );
};

$container[App\Handlers\LinkFetchHandler::class] = function ($c) {
    return new App\Handlers\LinkFetchHandler(
        $c['LinkStore'],
        $c['view']
    );
};

/*
|--------------------------------------------------------------------------
| Middlewares
|--------------------------------------------------------------------------
*/

$container[App\Middleware\RequestLogger::class] = function ($c) {
    return new App\Middleware\RequestLogger($c['logger']);
};

$app->add(App\Middleware\RequestLogger::class);

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

$app->get('/', function ($request, $response) {
    return $this->view->render($response, 'form.php');
});

$app->post('/', App\Handlers\LinkShortenHandler::class);

$app->get('/{shortLink}', App\Handlers\LinkFetchHandler::class);

return $app;
