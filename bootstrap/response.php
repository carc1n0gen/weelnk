<?php

return function ($c) {
    $headers = new Slim\Http\Headers(['Content-Type' => 'text/html; charset=UTF-8']);
    $response = new App\Http\Response(200, $headers);
    return $response->withProtocolVersion($c->get('settings')['httpVersion']);
};
