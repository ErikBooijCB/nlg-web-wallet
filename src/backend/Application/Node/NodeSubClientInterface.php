<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Node;

interface NodeSubClientInterface
{
    /**
     * @return string
     */
    public static function getName(): string;
}
