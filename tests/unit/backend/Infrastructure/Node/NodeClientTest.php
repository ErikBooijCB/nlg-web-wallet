<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Infrastructure\Node;

use GuldenWallet\Backend\Infrastructure\Node\NodeClient;
use GuldenWallet\Backend\Infrastructure\Node\NodeClientCredentials;
use GuldenWallet\Backend\Infrastructure\Node\NodeControlSubClient;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @covers \GuldenWallet\Backend\Infrastructure\Node\NodeClient
 */
class NodeClientTest extends TestCase
{
    /** @var Client|ObjectProphecy */
    private $httpClient;

    /** @var NodeClient */
    private $nodeClient;

    /**
     * @return void
     */
    public function setUp()
    {
        $credentials = new NodeClientCredentials('localhost', 9232, 'user', 'pass');

        $this->httpClient = self::prophesize(Client::class);

        $this->nodeClient = new NodeClient($this->httpClient->reveal(), $credentials);
    }

    /**
     * @return void
     */
    public function test_Namespaces_ShouldReturnTheirRespectiveSubClients()
    {
        // Add additional sub clients here

        self::assertInstanceOf(NodeControlSubClient::class, $this->nodeClient->control());
    }

    /**
     * @return void
     */
    public function test_Namespaces_ShouldReturnTheSameSubClientInstance_WhenInvokedMultipleTimes()
    {
        self::assertSame(
            $this->nodeClient->control(),
            $this->nodeClient->control()
        );
    }
}
