<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Tests\Unit\Infrastructure\Node;

use Exception;
use GuldenWallet\Backend\Infrastructure\Node\NodeResponse;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GuldenWallet\Backend\Infrastructure\Node\NodeResponse
 */
class NodeResponseTest extends TestCase
{
    /**
     * @return void
     */
    public function test_FromPsrResponse_ShouldCreateNodeResponse()
    {
        $response = new Response(200, [], json_encode([
            'result' => 'some-data'
        ]));

        $nodeResponse = NodeResponse::fromPsrResponse($response);

        self::assertTrue($nodeResponse->wasSuccessful());
        self::assertEquals(['some-data'], $nodeResponse->getData());
    }

    /**
     * @return void
     */
    public function test_ForFailedRequest_ShouldCreateNodeResponse()
    {
        $exception = new Exception('exception-message');

        $nodeResponse = NodeResponse::forFailedRequest($exception);

        self::assertFalse($nodeResponse->wasSuccessful());
        self::assertEquals('exception-message', $nodeResponse->getErrorInfo());
        self::assertEquals([], $nodeResponse->getData());
    }
}
