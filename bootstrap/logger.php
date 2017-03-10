<?php

return function ($c) {
    $logger = new \Monolog\Logger('weelnk');
    $file_handler = new \Monolog\Handler\StreamHandler(
        __DIR__.'/../storage/logs/weelnk.log',
        \Monolog\Logger::toMonologLevel(getenv('APP_LOG_LEVEL') ?: 'info')
    );
    $logger->pushHandler($file_handler);
    return $logger;
};
