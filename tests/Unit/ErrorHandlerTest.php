<?php

namespace Tests\Unit;

use Exception;
use Tests\TestCase;
use App\CookieHelper;
use Slim\Views\PhpRenderer;
use Psr\Log\LoggerInterface;
use App\Handlers\ErrorHandler;
use App\Errors\ValidationException;
use Carc1n0gen\ShortLink\Errors\DecodingException;

class ErrorHandlerTest extends TestCase
{
    protected static $app;
    protected static $container;

    public static function setUpBeforeClass()
    {
        self::$app = self::createApplication();
        self::$container = self::$app->getContainer();
    }

    public function testShouldRespondBadRequest()
    {
        $req = self::$container->get('request');
        $res = self::$container->get('response');
        $exception = new ValidationException('You dun goofed');
        $handler = new ErrorHandler(
            self::$container->get(PhPRenderer::class),
            self::$container->get(LoggerInterface::class),
            self::$container->get(CookieHelper::class)
        );

        $response = $handler($req, $res, $exception);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testShouldRespondNotFound()
    {
        $req = self::$container->get('request');
        $res = self::$container->get('response');
        $exception = new DecodingException('Where did the it go?');
        $handler = new ErrorHandler(
            self::$container->get(PhPRenderer::class),
            self::$container->get(LoggerInterface::class),
            self::$container->get(CookieHelper::class)
        );

        $response = $handler($req, $res, $exception);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testShouldRespondUnknownError()
    {
        $req = self::$container->get('request');
        $res = self::$container->get('response');
        $exception = new Exception('Sort of shit the fan didn\'t ya');
        $handler = new ErrorHandler(
            self::$container->get(PhPRenderer::class),
            self::$container->get(LoggerInterface::class),
            self::$container->get(CookieHelper::class)
        );

        $response = $handler($req, $res, $exception);
        $this->assertEquals(500, $response->getStatusCode());
    }
}