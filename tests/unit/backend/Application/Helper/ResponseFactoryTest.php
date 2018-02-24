<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Application\Helper;

use GuldenWallet\Backend\Application\Helper\ResponseFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \GuldenWallet\Backend\Application\Helper\ResponseFactory
 */
class ResponseFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function test_Success_ShouldReturnResponseWithStatusOkAnd200StatusCode_WhenStatusCodeNotDefined()
    {
        $data = ['key' => 'value'];

        $response = ResponseFactory::success($data);

        $parsedBody = $this->parseResponseBody($response);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertInternalType('array', $parsedBody);
        self::assertEquals('ok', $parsedBody['status']);
        self::assertEquals($data, $parsedBody['data']);
        self::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testSuccess_ShouldReturnResponseWithAlternativeStatusCode_WhenAlternativeStatusCodeIsDefined()
    {
        $response = ResponseFactory::success(['key' => 'value'], 204);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(204, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_SuccessMessage_ShouldReturnResponseWithStatusOkAnd200StatusCode_WhenStatusCodeNotDefined()
    {
        $message = 'Yay, success';

        $response = ResponseFactory::successMessage($message);

        $parsedBody = $this->parseResponseBody($response);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertInternalType('array', $parsedBody);
        self::assertEquals('ok', $parsedBody['status']);
        self::assertEquals($message, $parsedBody['message']);
        self::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testSuccessMessage_ShouldReturnResponseWithAlternativeStatusCode_WhenAlternativeStatusCodeIsDefined()
    {
        $response = ResponseFactory::successMessage('Yay, success', 204);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(204, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testFailure_ShouldReturnResponseWithStatusErrorAnd500StatusCode_WhenStatusCodeNotDefined()
    {
        $message = 'Oops, something went wrong';

        $response = ResponseFactory::failure($message);

        $parsedBody = $this->parseResponseBody($response);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertInternalType('array', $parsedBody);
        self::assertEquals('error', $parsedBody['status']);
        self::assertEquals($message, $parsedBody['message']);
        self::assertEquals(500, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testFailure_ShouldReturnResponseWithAlternativeStatusCode_WhenAlternativeStatusCodeIsDefined()
    {
        $response = ResponseFactory::success(['key' => 'value'], 419);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(419, $response->getStatusCode());
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array|bool
     */
    private function parseResponseBody(ResponseInterface $response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}
