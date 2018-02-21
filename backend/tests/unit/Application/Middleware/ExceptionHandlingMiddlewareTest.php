<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Application\Middleware;

use Exception;
use GuldenWallet\Backend\Application\Helper\Constant\Constant;
use GuldenWallet\Backend\Application\Helper\Constant\GlobalConstant;
use GuldenWallet\Backend\Application\Middleware\ExceptionHandlingMiddleware;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Finder\Glob;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @covers \GuldenWallet\Backend\Application\Middleware\ExceptionHandlingMiddleware
 */
class ExceptionHandlingMiddlewareTest extends TestCase
{
    /** @var ExceptionHandlingMiddleware */
    private $middleware;

    /** @var ServerRequestInterface|ObjectProphecy */
    private $request;

    /** @var ResponseInterface|ObjectProphecy */
    private $response;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->request = self::prophesize(ServerRequestInterface::class);
        $this->response = self::prophesize(ResponseInterface::class);

        $this->middleware = new ExceptionHandlingMiddleware;
    }

    /**
     * @return void
     */
    public function test_Invoke_ShouldReturnResponse_WhenNoExceptionIsUncaught()
    {
        $nextResponse = new JsonResponse([]);

        $next = function () use ($nextResponse) {
            return $nextResponse;
        };

        $response = call_user_func(
            $this->middleware,
            $this->request->reveal(),
            $this->response->reveal(),
            $next
        );

        self::assertSame($nextResponse, $response);
    }

    /**
     * @return void
     */
    public function test_Invoke_ShouldReturnFormatted500Response_WhenExceptionIsUncaught()
    {
        $next = function () {
            throw new Exception;
        };

        $response = call_user_func($this->middleware, $this->request->reveal(), $this->response->reveal(), $next);

        $responseData = json_decode($response->getBody()->getContents(), true);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(500, $response->getStatusCode());
        self::assertEquals('error', $responseData['status']);
        self::assertArrayHasKey('message', $responseData);
        self::assertArrayNotHasKey('error', $responseData);
    }

    /**
     * @return void
     */
    public function test_Invoke_ShouldIncludeErrorMessage_WhenRunOnDevelopment()
    {
        GlobalConstant::write(Constant::ENVIRONMENT, 'development');

        $next = function () {
            throw new Exception;
        };

        $response = call_user_func($this->middleware, $this->request->reveal(), $this->response->reveal(), $next);

        $responseData = json_decode($response->getBody()->getContents(), true);

        self::assertArrayHasKey('error', $responseData);
    }
}
