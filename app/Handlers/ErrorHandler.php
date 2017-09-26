<?php

namespace App\Handlers;

use Slim\Views\PhpRenderer;
use Psr\Log\LoggerInterface;
use App\Errors\ValidationException;
use Carc1n0gen\ShortLink\Errors\DecodingException;

/**
 * The application error handler
 *
 * This handler responds as json or web page depending on the request type
 */
class ErrorHandler
{
    protected $view;
    protected $logger;

    public function __construct(PhpRenderer $view, LoggerInterface $logger)
    {
        $this->view = $view;
        $this->logger = $logger;
    }

    public function __invoke($request, $response, $exception)
    {
        switch(get_class($exception))
        {
            case ValidationException::class:
                $status = 400;
                $data = ['code' => 'badRequest', 'msg' => $exception->getMessage()];
                $response = $request->isJson() ? $response->withJson($data) : $this->view->render($response, 'form.php', $data);
                break;

            case DecodingException::class:
                $status = 404;
                $data = ['code' => 'notFound','msg' => 'No link found'];
                $response = $request->isJson() ? $response->withJson($data) : $this->view->render($response, 'form.php', $data);
                break;

            default:
                $this->logger->addError('Unknown error', ['exception' => $exception]);
                $status = 500;
                $data = ['code' => 'unknownError', 'msg' => 'An unknow nerror occurred'];
                $response = $request->isJson() ? $response->withJson($data) : $this->view->render($response, 'form.php', $data);
        }

        return $response->withStatus($status);
    }
}
