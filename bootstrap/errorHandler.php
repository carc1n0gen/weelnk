<?php

use App\Handlers;

return function ($c) {
    return new Handlers\ErrorHandler($c);
};
