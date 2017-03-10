<?php

$app->get('/', function ($request, $response) {
    return $this->view->render($response, 'form.php');
});

$app->get('/{shortLink}', App\Http\Controllers\LinkFetchController::class);

$app->post('/', App\Http\Controllers\LinkShortenController::class);
