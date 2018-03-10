<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Support\Helpers\Node;

use GuldenWallet\Backend\Application\Node\NodeClientInterface;
use GuldenWallet\Backend\Infrastructure\Node\NodeControlSubClient;
use GuldenWallet\Backend\Infrastructure\Node\NodeResponse;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

trait NodeClientMockTrait
{
    /**
     * @param array $subClientOverrides
     * @return ObjectProphecy
     */
    protected function mockNodeClient(array $subClientOverrides = []): ObjectProphecy
    {
        $subClients = [
            'control' => NodeControlSubClient::class
        ];

        $nodeClient = self::prophesize(NodeClientInterface::class);

        foreach ($subClients as $namespace => $subClient) {
            $nodeClient->$namespace()->willReturn($subClientOverrides[$namespace] ?? self::prophesize($subClient));
        }

        return $nodeClient;
    }

    /**
     * @param bool $successFul
     * @param array $data
     * @param string $errorMessage
     * @return ObjectProphecy
     */
    public function mockNodeResponse(bool $successFul, array $data = [], string $errorMessage = ''): ObjectProphecy
    {
        $nodeResponse = self::prophesize(NodeResponse::class);

        $nodeResponse->getData()->willReturn($data);
        $nodeResponse->getErrorInfo()->willReturn($errorMessage);
        $nodeResponse->wasSuccessful()->willReturn($successFul);

        return $nodeResponse;
    }

    /**
     * @param mixed $data
     * @return ObjectProphecy
     */
    public function mockRpcResponse($data = ''): ObjectProphecy
    {
        $stream = self::prophesize(StreamInterface::class);
        $stream->getContents()->willReturn(json_encode([
            'result' => $data
        ]));

        $response = self::prophesize(ResponseInterface::class);

        $response->getBody()->willReturn($stream);

        return $response;
    }
}
