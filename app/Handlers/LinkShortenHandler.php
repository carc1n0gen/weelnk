<?php

namespace App\Handlers;

use App\Stores\LinkStore;
use Slim\Views\PhpRenderer;
use App\Errors\ValidationException;

/**
 *  Shorten a url and present the generated shortlink
 */
class LinkShortenHandler
{
    const PATTERN = '((https?:\/\/)?(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})';

    protected $linkStore;
    protected $view;

    public function __construct(LinkStore $linkStore, PhpRenderer $view)
    {
        $this->linkStore = $linkStore;
        $this->view = $view;
    }

    protected function validate($params)
    {
            if (!isset($params['url']) || !$params['url']) {
                throw new ValidationException('url parameter is required');
            }

            if (!preg_match(self::PATTERN, $params['url'])) {
                throw new ValidationException('The url format is invalid');
            }
    }

    public function __invoke($request, $response)
    {
        $this->validate($request->getParsedBody());

        $url = $request->getParsedBodyParam('url');
        $shortLink = $this->linkStore->findOrCreate($url);

        $data = [
            'code' => 'ok',
            'link' => $request->getSchemeAndHttpHost().'/'.$shortLink,
        ];
        return $request->isJson() ? $response->withJson($data)
            : $this->view->render($response, 'form.php', $data);
    }
}
