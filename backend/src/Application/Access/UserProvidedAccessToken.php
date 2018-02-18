<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Access;

class UserProvidedAccessToken
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

    /**
     * @return TokenIdentifier
     */
    public function getTokenIdentifier(): TokenIdentifier
    {
        return $this->tokenIdentifier;
    }
}
