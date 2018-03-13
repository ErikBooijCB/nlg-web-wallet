<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Application\Middleware;

use GuldenWallet\Backend\Application\Middleware\NotFoundHandlingMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @covers \GuldenWallet\Backend\Application\Middleware\NotFoundHandlingMiddleware
 */
class NotFoundHandlingMiddlewareTest extends TestCase
{
    /** @var NotFoundHandlingMiddleware */
    private $middleware;

    public function setUp()
    {
        $this->middleware = new NotFoundHandlingMiddleware;
    }

    /**
     * @param int $statusCode
     *
     * @return void
     *
     * @testWith [404]
     *           [405]
     */
    public function test_Invoke_ShouldReturn404WithMessage(int $statusCode)
    {
        $next = function () use ($statusCode) {
            return new EmptyResponse($statusCode);
        };

        $response = call_user_func(
            $this->middleware,
            self::prophesize(ServerRequestInterface::class)->reveal(),
            self::prophesize(ResponseInterface::class)->reveal(),
            $next
        );

        $responseData = json_decode($response->getBody()->getContents(), true);

        self::assertEquals(404, $response->getStatusCode());
        self::assertArrayHasKey('message', $responseData);
    }

    /**
     * @return void
     */
    public function test_Invoke_ShouldReturnNextResponse_WhenStatusCodeIsNot404Or405()
    {
        $nextResponse = new JsonResponse([]);

        $next = function () use ($nextResponse) {
            return $nextResponse;
        };

        $response = call_user_func(
            $this->middleware,
            self::prophesize(ServerRequestInterface::class)->reveal(),
            self::prophesize(ResponseInterface::class)->reveal(),
            $next
        );

        self::assertSame($nextResponse, $response);
    }
}
