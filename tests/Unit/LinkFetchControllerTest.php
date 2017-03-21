<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use App\Http\Controllers\LinkFetchController;
use Carc1n0gen\ShortLink\Errors\DecodingException;

class LinkFetchControllerTest extends TestCase
{
    protected static $app;
    protected static $controller;

    public static function setUpBeforeClass()
    {
        self::$app = self::createApplication();
        self::$controller = new LinkFetchController(self::$app->getContainer());

        self::$app->getContainer()->get('cache')->flush();
    }

    public function testShouldThrowDecodingException()
    {
        $this->expectException(DecodingException::class);

        $req = self::$app->getContainer()->get('request');
        $res = self::$app->getContainer()->get('response');
        $args = ['shortLink' => '!>,'];

        $controller = self::$controller;
        $controller($req, $res, $args);
    }

    public function testShouldRespondNotFound()
    {
        $req = self::$app->getContainer()->get('request');
        $res = self::$app->getContainer()->get('response');
        $args = ['shortLink' => 'abc'];

        $controller = self::$controller;
        $response = $controller($req, $res, $args);
        
        $this->assertEquals($response->getStatusCode(), 404);
    }

    public function testShouldRespondOk()
    {
        $req = self::$app->getContainer()->get('request');
        $res = self::$app->getContainer()->get('response');
        $args = ['shortLink' => 'b'];

        $controller = self::$controller;
        $response = $controller($req, $res, $args);

        $this->assertEquals($response->getStatusCode(), 302);
    }

    public function testShouldPullFromCacheIfCached()
    {
        // TODO: Need to create a spy the cache to test that cache->get was called.

        $req = self::$app->getContainer()->get('request');
        $res = self::$app->getContainer()->get('response');
        $args = ['shortLink' => 'b'];

        $controller = self::$controller;
        $response = $controller($req, $res, $args);

        $this->assertEquals($response->getStatusCode(), 302);
    }
}
