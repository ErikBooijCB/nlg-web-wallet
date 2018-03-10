<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Support\Fixtures\Access;

use DateTimeImmutable;
use DateTimeInterface;
use GuldenWallet\Backend\Application\Access\AccessToken;
use GuldenWallet\Backend\Application\Access\TokenIdentifier;

class AccessTokenFixture
{
    /**
     * @param string|null $identifier
     *
     * @return TokenIdentifier
     */
    public static function tokenIdentifier(string $identifier = null): TokenIdentifier
    {
        return TokenIdentifier::fromString(
            $identifier ?? '1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef'
        );
    }

    /**
     * @return TokenIdentifier
     */
    public static function randomTokenIdentifier(): TokenIdentifier
    {
        return TokenIdentifier::generate();
    }

    /**
     * @param TokenIdentifier   $accessToken
     * @param DateTimeInterface $expires
     * @param TokenIdentifier   $refeshToken
     *
     * @return AccessToken
     */
    public static function accessToken(
        TokenIdentifier $accessToken = null,
        DateTimeInterface $expires = null,
        TokenIdentifier $refeshToken = null
    ): AccessToken {
        return new AccessToken(
            $accessToken ?? self::randomTokenIdentifier(),
            $expires ?? new DateTimeImmutable('2018-10-12 12:34:56'),
            $refeshToken ?? self::randomTokenIdentifier()
        );
    }
}
