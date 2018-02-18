<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Access;

use GuldenWallet\Backend\Domain\Access\TokenIdentifier;

class UserProvidedRefreshToken
{
    /** @var TokenIdentifier */
    private $tokenIdentifier;

    /**
     * @param TokenIdentifier $tokenIdentifier
     */
    public function __construct(TokenIdentifier $tokenIdentifier)
    {
        $this->tokenIdentifier = $tokenIdentifier;
    }
}
