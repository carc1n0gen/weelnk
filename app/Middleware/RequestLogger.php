<?php

namespace App\Middleware;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
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
