<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Infrastructure\Controller;

use GuldenWallet\Backend\Infrastructure\Controller\NodeInformationHttpController;
use GuldenWallet\Backend\Infrastructure\Node\Exception\NodeRequestFailedException;
use GuldenWallet\Backend\Infrastructure\Node\NodeControlSubClient;
use GuldenWallet\Tests\Support\Fixtures\Node\NodeOverviewFixture;
use GuldenWallet\Tests\Support\Helpers\Node\NodeClientMockTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GuldenWallet\Backend\Infrastructure\Controller\NodeInformationHttpController
 */
class NodeInformationHttpControllerTest extends TestCase
{
    use NodeClientMockTrait;

    /**
     * @return void
     */
    public function test_Overview_ShouldProvideFieldsInResponse_WhenInvoked()
    {
        $controlSubClient = self::prophesize(NodeControlSubClient::class);
        $controlSubClient->getNodeInformation()->willReturn(NodeOverviewFixture::standard());

        $nodeClient = self::mockNodeClient([
            'control' => $controlSubClient
        ]);

        $controller = new NodeInformationHttpController($nodeClient->reveal());

        $response = $controller->overview();

        $parsedResponse = json_decode($response->getBody()->getContents(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('balance', $parsedResponse['data']);
        self::assertArrayHasKey('blocks', $parsedResponse['data']);
        self::assertArrayHasKey('connections', $parsedResponse['data']);
        self::assertArrayHasKey('testnet', $parsedResponse['data']);
        self::assertArrayHasKey('version', $parsedResponse['data']);
    }

    /**
     * @return void
     */
    public function test_Overview_ShouldFailGracefully_WhenRequestToGuldenNodeFails()
    {
        $controlSubClient = self::prophesize(NodeControlSubClient::class);
        $controlSubClient->getNodeInformation()->willThrow(new NodeRequestFailedException);

        $nodeClient = self::mockNodeClient([
            'control' => $controlSubClient
        ]);

        $controller = new NodeInformationHttpController($nodeClient->reveal());

        $response = $controller->overview();

        self::assertEquals(500, $response->getStatusCode());
    }
}
