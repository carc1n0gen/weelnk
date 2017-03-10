<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;

/**
 * Fech the url for a given shortlink
 */
class LinkFetchController extends Controller
{
    public function __invoke($request, $response, $args)
    {
        if ($url = $this->cache->get($args['shortLink'])) {
            $data = ['code' => 'ok', 'url' => $url];
            $response = $request->isJson() ? $response->withJson($data) : $response->redirect($url);
        } else {
            $id = $this->shortlink->decode($args['shortLink']);
            $link = $this->db->table('links')->find($id);

            if ($link) {
                $this->cache->put($args['shortLink'], $link->url, Carbon::now()->addDay());
                $data = ['code' => 'ok', 'url' => $link->url];
                $response = $request->isJson() ? $response->withJson($data) : $response->redirect($link->url);
            } else {
                $data = ['code' => 'notFound','msg' => 'No link found'];
                $response = $request->isJson() ? $response->withJson($data) : $this->view->render($response, 'form.php', $data);
            }
        }

        return $response;
    }
}