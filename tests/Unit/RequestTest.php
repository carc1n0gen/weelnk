<?php

namespace Tests\Unit;

use Slim\Http\Uri;
use Slim\Http\Body;
use Tests\TestCase;
use App\Http\Request;
use Slim\Http\Headers;
use Slim\Http\Environment;

class RequestTest extends TestCase
{
    protected static $app;

    public static function setUpBeforeClass()
    {
        self::$app = self::createApplication();
    }

    public function testGetSchemeAndHttpHost()
    {
        $body = 'url=https%3A%2F%2Fgoogle.ca';
        $headers = new Headers();
        $headers->set('Content-type', 'application/x-www-form-urlencoded');
        $req = new Request('POST', new Uri('http', 'test.com', null, '/this/path'), $headers, [], [], new Body(fopen("data://text/plain,$body", 'r')), []);

        $this->assertEquals($req->getSchemeAndHttpHost(), 'http://test.com');
    }

    public function testGetPath()
    {
        $body = 'url=https%3A%2F%2Fgoogle.ca';
        $headers = new Headers();
        $headers->set('Content-type', 'application/x-www-form-urlencoded');
        $req = new Request('POST', new Uri('http', 'test.com', null, '/this/path'), $headers, [], [], new Body(fopen("data://text/plain,$body", 'r')), []);

        $this->assertEquals($req->getPath(), '/this/path');
    }

    public function testGetRemoteAddress()
    {
        $body = 'url=https%3A%2F%2Fgoogle.ca';
        $headers = new Headers();
        $headers->set('Content-type', 'application/x-www-form-urlencoded');
        $req = new Request('POST', new Uri('http', 'test.com', null, '/this/path'), $headers, [], ['REMOTE_ADDR' => '127.0.0.1'], new Body(fopen("data://text/plain,$body", 'r')), []);

        $this->assertEquals($req->getRemoteAddress(), '127.0.0.1');
    }

    public function testGetRemoteAddressNull()
    {
        $body = 'url=https%3A%2F%2Fgoogle.ca';
        $headers = new Headers();
        $headers->set('Content-type', 'application/x-www-form-urlencoded');
        $req = new Request('POST', new Uri('http', 'test.com', null, '/this/path'), $headers, [], [], new Body(fopen("data://text/plain,$body", 'r')), []);

        $this->assertNull($req->getRemoteAddress());
    }

    public function testIsJsonTrue()
    {
        $body = '{"name":"The Dude"}';
        $headers = new Headers();
        $headers->set('Content-type', 'application/json');
        $req = new Request('POST', new Uri('http', 'test.com', null, '/this/path'), $headers, [], ['REMOTE_ADDR' => '127.0.0.1'], new Body(fopen("data://text/plain,$body", 'r')), []);

        $this->assertTrue($req->isJson());
    }

    public function testIsJsonFalse()
    {
        $body = 'url=https%3A%2F%2Fgoogle.ca';
        $headers = new Headers();
        $headers->set('Content-type', 'application/x-www-form-urlencoded');
        $req = new Request('POST', new Uri('http', 'test.com', null, '/this/path'), $headers, [], ['REMOTE_ADDR' => '127.0.0.1'], new Body(fopen("data://text/plain,$body", 'r')), []);

        $this->assertFalse($req->isJson());
    }
}