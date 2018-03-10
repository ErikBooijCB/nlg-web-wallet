<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Infrastructure\Node;

use GuldenWallet\Backend\Infrastructure\Node\AbstractNodeSubClient;
use GuldenWallet\Backend\Infrastructure\Node\NodeControlSubClient;
use GuldenWallet\Backend\Infrastructure\Node\NodeResponse;
use GuldenWallet\Tests\Support\Helpers\CaptureArgumentTrait;
use GuldenWallet\Tests\Support\Helpers\Node\NodeClientCredentialsFixture;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\RequestInterface;

/**
 * @covers \GuldenWallet\Backend\Infrastructure\Node\AbstractNodeSubClient
 */
class AbstractNodeSubClientTest extends TestCase
{
    use CaptureArgumentTrait;

    /** @var Client|ObjectProphecy */
    private $httpClient;

    /** @var AbstractNodeSubClient */
    private $subClient;

    /**
     * @return void
     */
    public function setUp()
    {
        // Using the NodeControlSubClient here in order to be able to obtain an instance of the abstract class
        // This should be and remain interchangeable with any other child class of the AbstractNodeSubClient

        $this->httpClient = self::prophesize(Client::class);

        $this->subClient = new NodeControlSubClient(
            $this->httpClient->reveal(),
            NodeClientCredentialsFixture::standard('123.123.123.123', 1024, 'some-user', 'some-pass')
        );
    }

    /**
     * @return void
     */
    public function test_GetName_ShouldReturnTheNameOfTheSubClient()
    {
        self::assertEquals(get_class($this->subClient), $this->subClient->getName());
    }

    /**
     * @return void
     */
    public function test_ExecuteShouldSendRequestWithProperData()
    {
        $this->httpClient
            ->send(self::captureArgument($request))
            ->willReturn(new Response());

        $response = $this->subClient->execute('method-name', 'argument');

        self::assertTrue($request->hasHeader('Content-type'));
        self::assertTrue($request->hasHeader('Authorization'));

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('//123.123.123.123:1024', (string)$request->getUri());
        self::assertEquals('application/json', $request->getHeaderLine('Content-type'));
        self::assertEquals('Basic c29tZS11c2VyOnNvbWUtcGFzcw==', $request->getHeaderLine('Authorization'));

        $body = json_decode($request->getBody()->getContents(), true);

        self::assertEquals('method-name', $body['method']);
        self::assertEquals(['argument'], $body['params']);
        self::assertArrayHasKey('id', $body);

        self::assertInstanceOf(NodeResponse::class, $response);
        self::assertTrue($response->wasSuccessful());
    }

    /**
     * @return void
     */
    public function test_ExecuteShouldReturnFailedNodeResponse_WhenExceptionOccurs()
    {
        $this->httpClient
            ->send(Argument::type(RequestInterface::class))
            ->willThrow(new RequestException('message', self::prophesize(RequestInterface::class)->reveal()));

        $response = $this->subClient->execute('method-name', 'argument');

        self::assertInstanceOf(NodeResponse::class, $response);
        self::assertFalse($response->wasSuccessful());
        self::assertEquals('message', $response->getErrorInfo());
    }
}
