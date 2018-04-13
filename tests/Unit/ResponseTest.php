<?php

namespace Tests\Unit;

use App\Request;
use Slim\Http\Uri;
use Slim\Http\Body;
use Tests\TestCase;
use Slim\Http\Headers;
use Slim\Http\Environment;

class ResponseTest extends TestCase
{
    protected $app;

    public function setUp()
    {
        $this->app = $this->createApplication();
    }

    public function testGetSchemeAndHttpHost()
    {
        $response = $this->app->getContainer()->get('response');
        $response = $response->redirect('https://google.ca');
        $headers = $response->getHeaders();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('https://google.ca', $headers['Location'][0]);
    }
}