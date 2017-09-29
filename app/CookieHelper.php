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
    public function get(ServerRequestInterface $request, string $key)
    {
        $cookies = $request->getCookieParams('cookies');
        return isset($cookies[$key]) ? $cookies[$key] : null;
    }

    // Pointless at this time
    // /**
    //  * Set a cookie
    //  *
    //  * @param $request The request to add cookies to
    //  * @param $key The name of the cookie
    //  * @param $val the value of the cookie
    //  * @return ServerRequestInterface A new request with the new cookies added to it
    //  */
    // public function set(ServerRequestInterface $request, $key, $val)
    // {
    //     return $request->withCookieParams(array_merge($request->getCookieParams(), [ $key, $val ]));
    // }
}