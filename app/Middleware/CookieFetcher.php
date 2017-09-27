<?php

namespace App\Middleware;

use Dflydev\FigCookies\Cookies;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CookieFetcher
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $cookies = Cookies::fromRequest($request);
        return $next($request->withAttribute('cookies', $cookies), $response);
    }
}
