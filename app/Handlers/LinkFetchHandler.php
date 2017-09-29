<?php

namespace App\Handlers;

use App\Request;
use App\Response;
use App\CookieHelper;
use App\Stores\LinkStore;
use Slim\Views\PhpRenderer;

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

    /**
     * @var CookieHelper
     */
    protected $cookies;

    public function __construct(LinkStore $linkStore, PhpRenderer $view, CookieHelper $cookies)
    {
        $this->linkStore = $linkStore;
        $this->view = $view;
        $this->cookies = $cookies;
    }

    public function __invoke(Request $request, Response $response, $shortLink)
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
