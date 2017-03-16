<?php

namespace App\Http\Middleware;

use Interop\Container\ContainerInterface;

/**
 * This middleware logs request information for each request
 */
class RequestLogger extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $this->logger->addInfo('Incoming request', [
            'ip' => $request->getRemoteAddress(),
            'path' => $request->getPath(),
            'queryParms' => $request->getQueryParams(),
            'headers' => $request->getHeaders(),
        ]);
        return $next($request, $response);
    }
}
