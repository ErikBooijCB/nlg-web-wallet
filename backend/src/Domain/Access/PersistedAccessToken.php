<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Domain\Access;

use DateTimeInterface;

class PersistedAccessToken
{
    /** @var DateTimeInterface */
    private $expires;

    /** @var PersistedRefreshToken */
    private $refreshToken;

    /** @var TokenIdentifier */
    private $tokenIdentifier;

    /**
     * @param TokenIdentifier       $tokenIdentifier
     * @param DateTimeInterface     $expires
     * @param PersistedRefreshToken $refreshToken
     */
    public function __construct(
        TokenIdentifier $tokenIdentifier,
        DateTimeInterface $expires,
        PersistedRefreshToken $refreshToken
    ) {
        $this->tokenIdentifier = $tokenIdentifier;
        $this->expires = $expires;
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return DateTimeInterface
     */
    public function getExpires(): DateTimeInterface
    {
        return $this->expires;
    }

    /**
     * @return PersistedRefreshToken
     */
    public function getRefreshToken(): PersistedRefreshToken
    {
        return $this->refreshToken;
    }

    /**
     * @return TokenIdentifier
     */
    public function getTokenIdentifier(): TokenIdentifier
    {
        return $this->tokenIdentifier;
    }
}
