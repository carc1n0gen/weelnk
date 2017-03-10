<?php

return function ($c) {
    return App\Http\Request::createFromEnvironment($c->get('environment'));
};
