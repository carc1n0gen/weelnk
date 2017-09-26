<?php

namespace App\Handlers;

use App\Stores\LinkStore;
use Slim\Views\PhpRenderer;

/**
 * Fech the url for a given shortlink
 */
class LinkFetchHandler
{
    protected $linkStore;
    protected $view;

    public function __construct(LinkStore $linkStore, PhpRenderer $view)
    {
        $this->linkStore = $linkStore;
        $this->view = $view;
    }

    public function __invoke($request, $response, $args)
    {
        $url = $this->linkStore->find($args['shortLink']);
        if ($url) {
            $data = ['code' => 'ok', 'url' => $url];
            $response = $request->isJson() ? $response->withJson($data)
                : $response->redirect($url);
        } else {
            $data = ['code' => 'notFound', 'msg' => 'No link found'];
            $response = $request->isJson() ? $response->withJson($data, 404)
                : $this->view->render($response, 'form.php', $data)->withStatus(404);
        }

        return $response;
    }
}
