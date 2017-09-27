<?php

namespace App\Handlers;


use App\Cookies;
use App\Stores\LinkStore;
use Slim\Views\PhpRenderer;
use App\Errors\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 *  Shorten a url and present the generated shortlink
 */
class LinkShortenHandler
{
    const PATTERN = '((https?:\/\/)?(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})';

    protected $linkStore;
    protected $view;
    protected $cookies;

    public function __construct(LinkStore $linkStore, PhpRenderer $view, Cookies $cookies)
    {
        $this->linkStore = $linkStore;
        $this->view = $view;
        $this->cookies = $cookies;
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

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->validate($request->getParsedBody());

        $url = $request->getParsedBodyParam('url');
        $shortLink = $this->linkStore->findOrCreate($url);

        $data = [
            'code' => 'ok',
            'link' => $request->getSchemeAndHttpHost().'/'.$shortLink,
        ];

        if (!$request->isJson()) {
            $data['theme'] = $this->cookies->get('theme') ?: 'light';
        }
        
        return $request->isJson() ? $response->withJson($data)
            : $this->view->render($response, 'form.php', $data);
    }
}
