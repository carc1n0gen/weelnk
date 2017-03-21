<?php

namespace Tests\Unit;

use Exception;
use Tests\TestCase;
use App\Handlers\ErrorHandler;
use App\Errors\ValidationException;
use Carc1n0gen\ShortLink\Errors\DecodingException;

class ErrorHandlerTest extends TestCase
{
    protected static $app;

    public static function setUpBeforeClass()
    {
        self::$app = self::createApplication();
    }

    public function testShouldRespondBadRequest()
    {
        $req = self::$app->getContainer()->get('request');
        $res = self::$app->getContainer()->get('response');
        $exception = new ValidationException('You dun goofed');
        $handler = new ErrorHandler(self::$app->getContainer());

        $response = $handler($req, $res, $exception);
        $this->assertEquals($response->getStatusCode(), 400);
    }

    public function testShouldRespondNotFound()
    {
        $req = self::$app->getContainer()->get('request');
        $res = self::$app->getContainer()->get('response');
        $exception = new DecodingException('Where did the it go?');
        $handler = new ErrorHandler(self::$app->getContainer());

        $response = $handler($req, $res, $exception);
        $this->assertEquals($response->getStatusCode(), 404);
    }

    public function testShouldRespondUnknownError()
    {
        $req = self::$app->getContainer()->get('request');
        $res = self::$app->getContainer()->get('response');
        $exception = new Exception('Sort of shit the fan didn\'t ya');
        $handler = new ErrorHandler(self::$app->getContainer());

        $response = $handler($req, $res, $exception);
        $this->assertEquals($response->getStatusCode(), 500);
    }
}