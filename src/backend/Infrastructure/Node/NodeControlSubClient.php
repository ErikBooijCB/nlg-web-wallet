<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Node;

use GuldenWallet\Backend\Application\Node\NodeControlSubClientInterface;
use GuldenWallet\Backend\Domain\Node\NodeOverview;
use GuldenWallet\Backend\Domain\Node\VersionMapper;
use GuldenWallet\Backend\Infrastructure\Node\Exception\NodeRequestFailedException;

class NodeControlSubClient extends AbstractNodeSubClient implements NodeControlSubClientInterface
{
    /**
     * @inheritdoc
     */
    public function getNodeInformation(): NodeOverview
    {
        $response = $this->execute('getinfo');

        if (!$response->wasSuccessful()) {
            throw NodeRequestFailedException::withMessage('Could not fetch node information');
        }

        $data = $response->getData();

        return new NodeOverview(
            VersionMapper::asString($data['version']),
            (int)$data['walletversion'],
            (int)$data['protocolversion'],
            (int)$data['connections'],
            (int)$data['blocks'],
            (float)$data['difficulty'],
            (float)$data['balance'],
            (int)$data['keypoolsize'],
            (bool)$data['testnet']
        );
    }
}

