<?php

use App\Cookies;
use Slim\Views\PhpRenderer;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use League\Flysystem\Filesystem;
use App\Handlers\LinkFetchHandler;
use Carc1n0gen\ShortLink\Converter;
use App\Handlers\LinkShortenHandler;
use Psr\Http\Message\ResponseInterface;
use Cache\Adapter\Common\AbstractCachePool;
use Psr\Http\Message\ServerRequestInterface;

/*
|--------------------------------------------------------------------------
| Initialize the slim app
|--------------------------------------------------------------------------
*/

$app = new DI\Bridge\Slim\App();

/*
|--------------------------------------------------------------------------
| Setup the dependencies in the DI container
|--------------------------------------------------------------------------
*/

$container = $app->getContainer();

$container->set('settings.displayErrorDetails', getenv('APP_DEBUG') ?: false);

$container->set('request', function ($c) {
    return App\Request::createFromEnvironment($c->get('environment'));
});

$container->set('response', function ($c) {
    $headers = new Slim\Http\Headers(['Content-Type' => 'text/html; charset=UTF-8']);
    $response = new App\Response(200, $headers);
    return $response->withProtocolVersion($c->get('settings')['httpVersion']);
});

$container->set('errorHandler', function ($c) {
    return new App\Handlers\ErrorHandler(
        $c->get(PhpRenderer::class),
        $c->get(LoggerInterface::class),
        new App\Cookies()
    );
});

$container->set(PhpRenderer::class, function ($c) {
    return new \Slim\Views\PhpRenderer(__DIR__.'/../app/views/');
});

$container->set(Connection::class, function ($c) {
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
});

$container->set(Filesystem::class, function ($c) {
    $adapter = new League\Flysystem\Adapter\Local(__DIR__.'/../storage');
    return new League\Flysystem\Filesystem($adapter);
});

$container->set(AbstractCachePool::class, function ($c) {
    $driver = getenv('CACHE_DRIVER') ?: 'file';
    switch($driver) {
        case 'array':
            $pool = new Cache\Adapter\PHPArray\ArrayCachePool();
            break;

        case 'file':
            $pool = new Cache\Adapter\Filesystem\FilesystemCachePool($c->get(Filesystem::class));
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
});

$container->set(LoggerInterface::class, function ($c) {
    // TODO: add syslog options
    $logger = new Monolog\Logger('weelnk');
    $file_handler = new Monolog\Handler\StreamHandler(
        __DIR__.'/../storage/logs/weelnk.log',
        Monolog\Logger::toMonologLevel(getenv('APP_LOG_LEVEL') ?: 'info')
    );
    $logger->pushHandler($file_handler);
    return $logger;
});

$container->set(Converter::class, function ($c) {
    return new Carc1n0gen\ShortLink\Converter(
        'abcdefghijklmnopqrswpwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );
});

/*
|--------------------------------------------------------------------------
| Middlewares
|--------------------------------------------------------------------------
*/

$app->add(App\Middleware\CookieFetcher::class);
$app->add(App\Middleware\RequestLogger::class);

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response, PhpRenderer $view, Cookies $cookies) {
    $theme = $cookies->get($request, 'theme') ?: 'light';
    return $view->render($response, 'form.php', ['theme' => $theme]);
});

$app->post('/', LinkShortenHandler::class);

$app->get('/{shortLink}', LinkFetchHandler::class);

return $app;
