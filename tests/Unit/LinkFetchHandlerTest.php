<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use App\CookieHelper;
use App\Stores\LinkStore;
use Slim\Views\PhpRenderer;
use App\Handlers\LinkFetchHandler;
use Carc1n0gen\ShortLink\Errors\DecodingException;

class LinkFetchHandlerTest extends TestCase
{
    protected $app;
    protected $controller;

    public function setUp()
    {
        $this->app = $this->createApplication();
        $this->controller = new LinkFetchHandler(
            $this->app->getContainer()->get(LinkStore::class),
            $this->app->getContainer()->get(PhpRenderer::class),
            $this->app->getContainer()->get(CookieHelper::class)
        );
    }

    public function testShouldThrowDecodingException()
    {
        $this->expectException(DecodingException::class);

        $req = $this->app->getContainer()->get('request');
        $res = $this->app->getContainer()->get('response');

        $controller = $this->controller;
        $controller($req, $res, '_');
    }

    public function testShouldRespondNotFound()
    {
        $req = $this->app->getContainer()->get('request');
        $res = $this->app->getContainer()->get('response');

        $controller = $this->controller;
        $response = $controller($req, $res, 'abc');
        
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testShouldRespondWithRedirect()
    {
        $req = $this->app->getContainer()->get('request');
        $res = $this->app->getContainer()->get('response');

        $controller = $this->controller;
        $response = $controller($req, $res, 'b');

        $this->assertEquals(302, $response->getStatusCode());
    }
}
