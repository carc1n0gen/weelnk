<?php

namespace App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * A helper class to make working with cookies cookies easier
 */
class CookieHelper
{
    /**
     * Fetch a cookie
     *
     * @param $request The request to fetch the cookie from
     * @param $key The name of the cookie
     * @return string The value of the cookie
     */
    public function get(ServerRequestInterface $request)
    {
        $cookies = $request->getCookieParams('cookies');
        return isset($cookies[$key]) ? $cookies[$key] : null;
    }
}