<?php

namespace App\Handlers;

use App\Cookies;
use App\Stores\LinkStore;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Fech the url for a given shortlink
 */
class LinkFetchHandler
{
    /**
     * @var LinkStore
     */
    protected $linkStore;

    /**
     * @var PhpRenderer
     */
    protected $view;

    public function __construct(LinkStore $linkStore, PhpRenderer $view, Cookies $cookies)
    {
        $this->linkStore = $linkStore;
        $this->view = $view;
        $this->cookies = $cookies;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $shortLink)
    {
        $url = $this->linkStore->find($shortLink);
        if ($url) {
            $data = ['code' => 'ok', 'url' => $url];
            $response = $request->isJson() ? $response->withJson($data)
                : $response->redirect($url);
        } else {
            $data = ['code' => 'notFound', 'msg' => 'No link found'];
            if (!$request->isJson()) {
                $data['theme'] = $this->cookies->get($request, 'theme') ?: 'light';
            }
            $response = $request->isJson() ? $response->withJson($data, 404)
                : $this->view->render($response, 'form.php', $data)->withStatus(404);
        }

        return $response;
    }
}
