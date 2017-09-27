<?php

namespace App;

use Dflydev\FigCookies\Cookies as FigCookies;
use Psr\Http\Message\ServerRequestInterface;

class Cookies
{
    public function get(ServerRequestInterface $request, string $key)
    {
        $cookies = $request->getAttribute('cookies');

        if (!$cookies) {
            $cookies = FigCookies::fromRequest($request);
        }

        $cookie = $cookies->get($key);
        return $cookie->getValue();
    }
}