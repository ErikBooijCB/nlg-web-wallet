<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Controller;

use GuldenWallet\Backend\Application\Helper\ResponseFactory;
use GuldenWallet\Backend\Application\Node\NodeClientInterface;
use GuldenWallet\Backend\Infrastructure\Node\Exception\NodeRequestFailedException;
use Psr\Http\Message\ResponseInterface;

class NodeInformationHttpController
{
    /** @var NodeClientInterface */
    private $nodeClient;

    /**
     * @param NodeClientInterface $nodeClient
     */
    public function __construct(NodeClientInterface $nodeClient)
    {
        $this->nodeClient = $nodeClient;
    }

    /**
     * @return ResponseInterface
     */
    public function overview(): ResponseInterface
    {
        try {
            $nodeInformation = $this->nodeClient->control()->getNodeInformation();

            return ResponseFactory::success([
                'balance' => $nodeInformation->getBalance(),
                'blocks' => $nodeInformation->getBlocks(),
                'connections' => $nodeInformation->getConnections(),
                'testnet' => $nodeInformation->isTestNet(),
                'version' => $nodeInformation->getVersion()
            ]);
        } catch (NodeRequestFailedException $exception) {
            return ResponseFactory::failure('Could not retrieve status from Gulden node');
        }
    }
}
