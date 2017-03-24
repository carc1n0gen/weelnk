<?php

namespace App\Handlers;

use App\Component;
use App\Errors\ValidationException;

/**
 *  Shorten a url and present the generated shortlink
 */
class LinkShortenHandler extends Component
{
    const PATTERN = '((https?:\/\/)?(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})';

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
        // If the url does not include the protocol, assume http
        if (!preg_match('#^(.*:)?//#i', $url)) {
            $url = "http://$url";
        }
        $md5 = md5($url);
        
        $link = $this->db->table('links')->where('md5', $md5)->first();
        if ($link) {
            $id = $link->id;
        } else {
            $id = $this->db->table('links')->insertGetId(
                ['url' => $url, 'md5' => $md5]
            );
        }

        $data = [
            'code' => 'ok',
            'link' => $request->getSchemeAndHttpHost().'/'.$this->shortlink->encode($id),
        ];
        return $request->isJson() ? $response->withJson($data) : $this->view->render($response, 'form.php', $data);
    }
}