<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Node;

use GuldenWallet\Backend\Application\Node\NodeSubClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

abstract class AbstractNodeSubClient implements NodeSubClientInterface
{
    /** @var NodeClientCredentials */
    private $credentials;

    /** @var Client */
    private $httpClient;

    /**
     * @param Client $httpClient
     * @param NodeClientCredentials $credentials
     */
    public function __construct(Client $httpClient, NodeClientCredentials $credentials)
    {
        $this->httpClient = $httpClient;
        $this->credentials = $credentials;
    }

    /**
     * @param string $command
     * @param string[] $parameters
     * @return NodeResponse
     */
    public function execute(string $command, string ...$parameters): NodeResponse
    {
        $request = $this->prepareRequest($command, $parameters);

        try {
            $response = $this->httpClient->send($request);

            return NodeResponse::fromPsrResponse($response);
        } catch (RequestException $exception) {
            $nodeResponse = NodeResponse::forFailedRequest($exception);

            return $nodeResponse;
        }
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::class;
    }

    /**
     * @param string $command
     * @param array $parameters
     * @return RequestInterface
     */
    private function prepareRequest(string $command, array $parameters): RequestInterface
    {
        $parameters = array_filter(
            array_values($parameters),
            /** @psalm-suppress MissingClosureParamType */
            function ($parameter): bool {
                return $parameter !== null;
            }
        );

        return new Request(
            'POST',
            "{$this->credentials->getHost()}:{$this->credentials->getPort()}",
            [
                'Content-type' => 'application/json',
                'Authorization' => 'Basic ' .
                    base64_encode("{$this->credentials->getUsername()}:{$this->credentials->getPassword()}"),
            ],
            json_encode([
                'method' => $command,
                'params' => $parameters,
                'id' => uniqid('request_id_', true),
            ])
        );
    }
}
