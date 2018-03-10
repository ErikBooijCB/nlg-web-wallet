<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Node;

use GuldenWallet\Backend\Application\Node\NodeClientInterface;
use GuldenWallet\Backend\Application\Node\NodeControlSubClientInterface;
use GuldenWallet\Backend\Application\Node\NodeSubClientInterface;
use GuzzleHttp\Client;

class NodeClient implements NodeClientInterface
{
    /** @var NodeClientCredentials */
    private $credentials;

    /** @var Client */
    private $httpClient;

    /** @var  */
    private $subClients = [];

    /**
     * @param NodeClientCredentials $credentials
     */
    public function __construct(Client $httpClient, NodeClientCredentials $credentials)
    {
        $this->httpClient = $httpClient;
        $this->credentials = $credentials;
    }

    /**
     * @return NodeControlSubClientInterface
     */
    public function control(): NodeControlSubClientInterface
    {
        /** @var NodeControlSubClientInterface $subClient */
        $subClient = $this->getSubClient(NodeControlSubClient::class);

        return $subClient;
    }

    /**
     * @param string $client
     * @return NodeSubClientInterface
     */
    private function getSubClient(string $client): NodeSubClientInterface
    {
        if (!isset($this->subClients[$client]) || !$this->subClients[$client] instanceof $client) {
            $this->subClients[$client] = new $client($this->httpClient, $this->credentials);
        }

        return $this->subClients[$client];
    }
}
