<?php

namespace Tests\Unit;

use Slim\Http\Uri;
use Slim\Http\Body;
use Tests\TestCase;
use App\Request;
use Slim\Http\Headers;
use App\Errors\ValidationException;
use App\Handlers\LinkShortenHandler;

class LinkShortenHandlerTest extends TestCase
{
    protected $app;
    protected $controller;

    public function setUp()
    {
        $this->app = $this->createApplication();
        $this->controller = new LinkShortenHandler(
            $this->app->getContainer()->get('LinkStore'),
            $this->app->getContainer()->get('view')
        );
    }

    public function testShouldThrowValidationExceptionWithKnowParameter()
    {
        $this->expectException(ValidationException::class);

        $req = $this->app->getContainer()->get('request');
        $res = $this->app->getContainer()->get('response');

        $controller = $this->controller;
        $response = $controller($req, $res);
    }

    public function testShouldThrowValidationExceptionWithInvalidParameter()
    {
        $this->expectException(ValidationException::class);

        $body = 'url=abc';
        $headers = new Headers();
        $headers->set('Content-type', 'application/x-www-form-urlencoded');
        $req = new Request('POST', new Uri('http', 'test.com'), $headers, [], [], new Body(fopen("data://text/plain,$body", 'r')), []);
        $res = $this->app->getContainer()->get('response');

        $controller = $this->controller;
        $response = $controller($req, $res);
    }

    public function testShouldRespondOk()
    {
        $body = 'url=https%3A%2F%2Fgoogle.ca';
        $headers = new Headers();
        $headers->set('Content-type', 'application/x-www-form-urlencoded');
        $req = new Request('POST', new Uri('http', 'test.com'), $headers, [], [], new Body(fopen("data://text/plain,$body", 'r')), []);
        $res = $this->app->getContainer()->get('response');

        $controller = $this->controller;
        $response = $controller($req, $res);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @depends testShouldRespondOk
     */
    public function testShouldRespondOkWhenLinkAlreadyExists()
    {
        $body = 'url=https%3A%2F%2Fgoogle.ca';
        $headers = new Headers();
        $headers->set('Content-type', 'application/x-www-form-urlencoded');
        $req = new Request('POST', new Uri('http', 'test.com'), $headers, [], [], new Body(fopen("data://text/plain,$body", 'r')), []);
        $res = $this->app->getContainer()->get('response');

        $controller = $this->controller;
        $response = $controller($req, $res);

        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testShouldRespondOkayEvenIfHttpIsNotPresent()
    {
        $body = 'url=github.com%2Fcarc1n0gen%2Fphp-shortlink';
        $headers = new Headers();
        $headers->set('Content-type', 'application/x-www-form-urlencoded');
        $req = new Request('POST', new Uri('http', 'test.com'), $headers, [], [], new Body(fopen("data://text/plain,$body", 'r')), []);
        $res = $this->app->getContainer()->get('response');

        $controller = $this->controller;
        $response = $controller($req, $res);

        $this->assertEquals($response->getStatusCode(), 200);
    }
}
