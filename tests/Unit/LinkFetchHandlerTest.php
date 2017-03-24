<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use App\Handlers\LinkFetchHandler;
use Carc1n0gen\ShortLink\Errors\DecodingException;

class LinkFetchHandlerTest extends TestCase
{
    protected $app;
    protected $controller;

    public function setUp()
    {
        $this->app = self::createApplication();
        $this->controller = new LinkFetchHandler($this->app->getContainer());
    }

    public function testShouldThrowDecodingException()
    {
        $this->expectException(DecodingException::class);

        $req = $this->app->getContainer()->get('request');
        $res = $this->app->getContainer()->get('response');
        $args = ['shortLink' => '!>,'];

        $controller = $this->controller;
        $controller($req, $res, $args);
    }

    public function testShouldRespondNotFound()
    {
        $req = $this->app->getContainer()->get('request');
        $res = $this->app->getContainer()->get('response');
        $args = ['shortLink' => 'abc'];

        $controller = $this->controller;
        $response = $controller($req, $res, $args);
        
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testShouldRespondOk()
    {
        $req = $this->app->getContainer()->get('request');
        $res = $this->app->getContainer()->get('response');
        $args = ['shortLink' => 'b'];

        $controller = $this->controller;
        $response = $controller($req, $res, $args);

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testShouldPullFromCacheIfCached()
    {
        $mock = Mockery::mock();
        $mock->shouldReceive('has')->andReturn(true)->once();
        $mock->shouldReceive('get')->andReturn('https://google.ca')->once();
        $this->app->getContainer()['cache'] = $mock;

        $req = $this->app->getContainer()->get('request');
        $res = $this->app->getContainer()->get('response');
        $args = ['shortLink' => 'b'];

        $controller = $this->controller;
        $response = $controller($req, $res, $args);
        $this->assertEquals(302, $response->getStatusCode());
    }
}
