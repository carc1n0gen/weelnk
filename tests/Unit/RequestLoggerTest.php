<?php

namespace Test\Unit;

use Mockery;
use Monolog\Logger;
use Tests\TestCase;
use App\Middleware\RequestLogger;

class RequestLoggerTest extends TestCase
{
    protected $app;

    public function setUp()
    {
        $this->app = $this->createApplication();
    }

    public function testShouldLogInfo()
    {
        $mock = Mockery::mock(Logger::class.'[addInfo]', ['weelnk']);
        $mock->shouldReceive('addInfo')->once();
        $this->app->getContainer()->set('logger', $mock);

        $req = $this->app->getContainer()->get('request');
        $res = $this->app->getContainer()->get('response');
        $middleware = new RequestLogger(
            $this->app->getContainer()->get('logger')
        );

        $response = $middleware($req, $res, function ($req, $res) {
            return $res;
        });
    }
}