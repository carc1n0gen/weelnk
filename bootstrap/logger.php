<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

return function ($c) {
    $logger = new Logger('weelnk');
    $file_handler = new StreamHandler(
        __DIR__.'/../storage/logs/weelnk.log',
        Logger::toMonologLevel(getenv('APP_LOG_LEVEL') ?: 'info')
    );
    $logger->pushHandler($file_handler);
    return $logger;
};
