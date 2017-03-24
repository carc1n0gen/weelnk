<?php

namespace App\Handlers;

use DateTime;
use Exception;
use App\Component;

/**
 * Fech the url for a given shortlink
 */
class LinkFetchHandler extends Component
{
    public function __invoke($request, $response, $args)
    {
        if ($this->cache->has($args['shortLink'])) {
            $url = $this->cache->get($args['shortLink']);
            $data = ['code' => 'ok', 'url' => $url];
            $response = $request->isJson() ? $response->withJson($data) : $response->redirect($url);
        } else {
            $id = $this->shortlink->decode($args['shortLink']);
            $link = $this->db->table('links')->find($id);

            if ($link) {
                $this->cache->put($args['shortLink'], $link->url, (new DateTime('+24 hours'))->getTimeStamp());
                $data = ['code' => 'ok', 'url' => $link->url];
                $response = $request->isJson() ? $response->withJson($data) : $response->redirect($link->url);
            } else {
                $data = ['code' => 'notFound','msg' => 'No link found'];
                $response = $request->isJson() ? $response->withJson($data, 404) : $this->view->render($response, 'form.php', $data)->withStatus(404);
            }
        }

        return $response;
    }
}