<?php

namespace Tests\Unit;

use Slim\Http\Uri;
use Slim\Http\Body;
use Tests\TestCase;
use App\Http\Request;
use Slim\Http\Headers;
use Slim\Http\Environment;

class ResponseTest extends TestCase
{
    protected static $app;

    public static function setUpBeforeClass()
    {
        self::$app = self::createApplication();
    }

    public function testGetSchemeAndHttpHost()
    {
        $response = self::$app->getContainer()->get('response');
        $response = $response->redirect('https://google.ca');
        $headers = $response->getHeaders();

        $this->assertEquals($response->getStatusCode(), 302);
        $this->assertEquals($headers['Location'][0], 'https://google.ca');
    }
}