<?php

namespace App\Handlers;

use App\Request;
use App\Response;
use App\CookieHelper;
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
    protected $cookies;

    public function __construct(LinkStore $linkStore, PhpRenderer $view, CookieHelper $cookies)
    {
        /**
         * @var LinkStore
         */
        $this->linkStore = $linkStore;

        /**
         * @var PhpRenderer
         */
        $this->view = $view;

        /**
         * @var CookieHelper
         */
        $this->cookies = $cookies;
    }

    protected function validate($params)
    {
            if (!isset($params['url']) || !$params['url']) {
                throw new ValidationException('a url is required');
            }

            if (!preg_match(self::PATTERN, $params['url'])) {
                throw new ValidationException('The url format is invalid');
            }
    }

    public function __invoke(Request $request, Response $response)
    {
        $this->validate($request->getParsedBody());

        $url = $request->getParsedBodyParam('url');
        $shortLink = $this->linkStore->findOrCreate($url);

        $data = [
            'code' => 'ok',
            'link' => $request->getSchemeAndHttpHost().'/'.$shortLink,
        ];

        if (!$request->isJson()) {
            $data['theme'] = $this->cookies->get($request, 'theme') ?: 'light';
        }
        
        return $request->isJson() ? $response->withJson($data)
            : $this->view->render($response, 'form.php', $data);
    }
}
