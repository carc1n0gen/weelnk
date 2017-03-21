<?php

namespace Test\Unit;

use Mockery;
use Monolog\Logger;
use Tests\TestCase;
use App\Http\Middleware\RequestLogger;

class RequestLoggerTest extends TestCase
{
    protected static $app;

    public static function setUpBeforeClass()
    {
        self::$app = self::createApplication();
    }

    public function testShouldLogInfo()
    {
        $mock = Mockery::mock(Logger::class.'[addInfo]', ['weelnk']);
        $mock->shouldReceive('addInfo')->once();
        self::$app->getContainer()['logger'] = $mock;

        $req = self::$app->getContainer()->get('request');
        $res = self::$app->getContainer()->get('response');
        $middleware = new RequestLogger(self::$app->getContainer());

        $response = $middleware($req, $res, function ($req, $res) {
            return $res;
        });

        Mockery::close();
    }
}