<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Node;

use GuldenWallet\Backend\Domain\Node\NodeOverview;

interface NodeControlSubClientInterface extends NodeSubClientInterface
{
    /** @var string */
    const SUB_CLIENT_IDENTIFIER = 'control';

    /**
     * @return NodeOverview
     */
    public function getNodeInformation(): NodeOverview;
}
