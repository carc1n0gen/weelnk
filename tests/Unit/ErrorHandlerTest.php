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
    protected $app;
    protected $container;

    public function setUp()
    {
        $this->app = $this->createApplication();
        $this->container = $this->app->getContainer();
    }

    public function testShouldRespondBadRequest()
    {
        $req = $this->container->get('request');
        $res = $this->container->get('response');
        $exception = new ValidationException('You dun goofed');
        $handler = new ErrorHandler(
            $this->container->get(PhPRenderer::class),
            $this->container->get(LoggerInterface::class),
            $this->container->get(CookieHelper::class)
        );

        $response = $handler($req, $res, $exception);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testShouldRespondNotFound()
    {
        $req = $this->container->get('request');
        $res = $this->container->get('response');
        $exception = new DecodingException('Where did the it go?');
        $handler = new ErrorHandler(
            $this->container->get(PhPRenderer::class),
            $this->container->get(LoggerInterface::class),
            $this->container->get(CookieHelper::class)
        );

        $response = $handler($req, $res, $exception);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testShouldRespondUnknownError()
    {
        $req = $this->container->get('request');
        $res = $this->container->get('response');
        $exception = new Exception('Sort of shit the fan didn\'t ya');
        $handler = new ErrorHandler(
            $this->container->get(PhPRenderer::class),
            $this->container->get(LoggerInterface::class),
            $this->container->get(CookieHelper::class)
        );

        $response = $handler($req, $res, $exception);
        $this->assertEquals(500, $response->getStatusCode());
    }
}