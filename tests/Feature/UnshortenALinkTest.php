<?php

namespace Tests\Feature;

use Tests\TestCase;
use Slim\Http\Environment;

class UnshortenALinkTest extends TestCase
{
    protected $app;

    public function setUp()
    {
        $this->app = self::createApplication();
    }
    
    public function testThatIt404sWhenLinkDoesNotExist()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/z',
            'SERVER_NAME' => 'test.com',
            'HTTP_HOST' => 'test.com',
        ]);
        $this->app->getContainer()['environment'] = $env;

        $response = $this->app->run(true);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testThatIt404sWhenShortlinkIsBad()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/!@^',
            'SERVER_NAME' => 'test.com',
            'HTTP_HOST' => 'test.com',
        ]);
        $this->app->getContainer()['environment'] = $env;

        $response = $this->app->run(true);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testThatItRedirectsWhenAShortlinkExists()
    {
        $_POST['url'] = 'https://google.com';
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/',
            'SERVER_NAME' => 'test.com',
            'HTTP_HOST' => 'test.com',
            'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
        ]);
        $this->app->getContainer()['environment'] = $env;

        $response = $this->app->run(true);
        $body = $response->getBody();
        $body->rewind();
        $html = $body->getContents();

        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $link = $dom->getElementById('short-link');
        $url = parse_url($link->textContent);

        $this->app = self::createApplication(); // re-init the app
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => $url['path'],
            'SERVER_NAME' => 'test.com',
            'HTTP_HOST' => 'test.com',
        ]);
        $this->app->getContainer()['environment'] = $env;

        $response = $this->app->run(true);
        $headers = $response->getHeaders();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('https://google.com', $headers['Location'][0]);
    }
}