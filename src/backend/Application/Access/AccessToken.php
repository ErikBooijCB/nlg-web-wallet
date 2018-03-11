<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Access;

use DateTimeInterface;

/**
 * @codeCoverageIgnore
 */
class AccessToken
{
    /** @var DateTimeInterface */
    private $expires;

    /** @var TokenIdentifier */
    private $refreshToken;

    /** @var TokenIdentifier */
    private $tokenIdentifier;

    /**
     * @param TokenIdentifier $tokenIdentifier
     * @param DateTimeInterface $expires
     * @param TokenIdentifier $refreshToken
     */
    public function __construct(
        TokenIdentifier $tokenIdentifier,
        DateTimeInterface $expires,
        TokenIdentifier $refreshToken
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
     * @return TokenIdentifier
     */
    public function getRefreshTokenIdentifier(): TokenIdentifier
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
