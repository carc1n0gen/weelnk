<?php

namespace App\Http;

use Slim\Http\Response as SlimResponse;

/**
 * The app response type
 *
 * This response class just extends the Slim\Http\Response and adds helper methods
 */
class Response extends SlimResponse
{
    public function redirect($url)
    {
        return $this->withStatus(302)->withHeader('Location', $url);
    }
}
