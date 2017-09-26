<?php

namespace App\Middleware;

use Psr\Log\LoggerInterface;

/**
 * This middleware logs request information for each request
 */
class RequestLogger
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

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
