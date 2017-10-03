<?php

namespace App\Handlers;

use App\Request;
use App\Response;
use App\CookieHelper;
use Slim\Views\PhpRenderer;
use Psr\Log\LoggerInterface;
use App\Errors\ValidationException;
use Carc1n0gen\ShortLink\Errors\DecodingException;
use Cache\Adapter\Common\Exception\InvalidArgumentException;

/**
 * The application error handler
 *
 * This handler responds as json or web page depending on the request type
 */
class ErrorHandler
{
    /**
     * @var PhpRenderer
     */
    protected $view;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var CookieHelper
     */
    protected $cookies;

    public function __construct(PhpRenderer $view, LoggerInterface $logger, CookieHelper $cookies)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->cookies = $cookies;
    }

    public function __invoke(Request $request, Response $response, $exception)
    {
        switch(get_class($exception))
        {
            case ValidationException::class:
                $status = 400;
                $data = ['code' => 'badRequest', 'msg' => $exception->getMessage()];
                if (!$request->isJson()) {
                    $data['theme'] = $this->cookies->get($request, 'theme') ?: 'light';
                }
                $response = $request->isJson() ? $response->withJson($data) : $this->view->render($response, 'form.php', $data);
                break;

            case DecodingException::class:
            case InvalidArgumentException::class:
                $status = 404;
                $data = ['code' => 'notFound', 'msg' => 'No link found'];
                if (!$request->isJson()) {
                    $data['theme'] = $this->cookies->get($request, 'theme') ?: 'light';
                }
                $response = $request->isJson() ? $response->withJson($data) : $this->view->render($response, 'form.php', $data);
                break;

            default:
                $this->logger->addError('Unknown error', ['exception' => $exception]);
                $status = 500;
                $data = ['code' => 'unknownError', 'msg' => 'An unknow nerror occurred'];
                if (!$request->isJson()) {
                    $data['theme'] = $this->cookies->get($request, 'theme') ?: 'light';
                }
                $response = $request->isJson() ? $response->withJson($data) : $this->view->render($response, 'form.php', $data);
        }

        return $response->withStatus($status);
    }
}
