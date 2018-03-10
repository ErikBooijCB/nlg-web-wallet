<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Infrastructure\Node;

use GuldenWallet\Backend\Domain\Node\NodeOverview;
use GuldenWallet\Backend\Infrastructure\Node\Exception\NodeRequestFailedException;
use GuldenWallet\Backend\Infrastructure\Node\NodeControlSubClient;
use GuldenWallet\Tests\Support\Fixtures\Node\NodeOverviewFixture;
use GuldenWallet\Tests\Support\Helpers\Node\NodeClientCredentialsFixture;
use GuldenWallet\Tests\Support\Helpers\Node\NodeClientMockTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\RequestInterface;

/**
 * @covers \GuldenWallet\Backend\Infrastructure\Node\NodeControlSubClient
 */
class NodeControlSubClientTest extends TestCase
{
    use NodeClientMockTrait;

    /** @var Client|ObjectProphecy */
    private $httpClient;

    /** @var NodeControlSubClient */
    private $subClient;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->httpClient = self::prophesize(Client::class);

        $this->subClient = new NodeControlSubClient(
            $this->httpClient->reveal(),
            NodeClientCredentialsFixture::standard()
        );
    }

    /**
     * @return void
     */
    public function test_GetNodeInformation_ShouldThrowProperException_WhenRPCCallFails()
    {
        self::expectException(NodeRequestFailedException::class);

        $this->httpClient->send(Argument::type(RequestInterface::class))->willThrow(
            self::prophesize(RequestException::class)->reveal()
        );

        $this->subClient->getNodeInformation();
    }

    /**
     * @return void
     */
    public function test_GetNodeInformation_ShouldReturnNoeOverview()
    {
        $this->httpClient->send(Argument::type(RequestInterface::class))->willReturn(
            self::mockRpcResponse(NodeOverviewFixture::raw())
        );

        $nodeOverview = $this->subClient->getNodeInformation();

        self::assertInstanceOf(NodeOverview::class, $nodeOverview);
    }
}
