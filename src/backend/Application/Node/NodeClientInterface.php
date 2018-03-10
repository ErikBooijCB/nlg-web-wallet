<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Node;

interface NodeClientInterface
{
    /**
     * @return NodeControlSubClientInterface
     */
    public function control(): NodeControlSubClientInterface;
}
