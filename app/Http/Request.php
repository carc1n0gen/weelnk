<?php

namespace App\Http;

use Slim\Http\Request as SlimRequest;

/**
 * The app request type
 *
 * This request class just extends the Slim\Http\Request and adds helper methods
 */
class Request extends SlimRequest
{
    public function getSchemeAndHttpHost()
    {
        $uri = $this->getUri();
        return $uri->getScheme() . '://' . $uri->getAuthority();
    }

    public function getPath()
    {
        return  $this->uri->getPath();
    }

    public function getRemoteAddress()
    {
        $serverParams = $this->getServerParams();
        if (isset($serverParams['REMOTE_ADDR'])) {
            return $serverParams['REMOTE_ADDR'];
        }

        return null;
    }

    public function isJson()
    {
        return $this->getContentType() === 'application/json';
    }
}
