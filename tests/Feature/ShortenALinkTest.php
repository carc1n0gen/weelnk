<?php

namespace Tests\Feture;

use Tests\TestCase;
use Slim\Http\Environment;

class ShortenALinkTest extends TestCase
{
    protected $app;

    public function setUp()
    {
        $this->app = self::createApplication();
    }

    public function testThatItFailsWithNoUrl()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/',
            'SERVER_NAME' => 'test.com',
            'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
        ]);
        $this->app->getContainer()->set('environment', $env);

        $response = $this->app->run(true);
        $this->assertEquals($response->getStatusCode(), 400);
    }

    public function testThatItFailsWhenUrlIsInvalid()
    {
        $badUrls = [
            'asdf',
            '2345',
            'http://',
            'http:google.ca',
            '1234://google.ca',
            'http://google',
            'http://google.',
        ];

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/',
            'SERVER_NAME' => 'test.com',
            'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
        ]);
        $this->app->getContainer()->set('environment', $env);

        foreach($badUrls as $badUrl) {
            $_POST['url'] = $badUrl;

            $response = $this->app->run(true);
            $this->assertEquals(400, $response->getStatusCode());
        }
    }

    public function testThatALinkCanBeShortened()
    {
        $_POST['url'] = 'https://google.ca';
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/',
            'SERVER_NAME' => 'test.com',
            'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
        ]);
        $this->app->getContainer()->set('environment', $env);

        $response = $this->app->run(true);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
