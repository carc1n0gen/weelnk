<?php

return function ($c) {
    return new \Slim\Views\PhpRenderer(__DIR__.'/../app/views/');
};
