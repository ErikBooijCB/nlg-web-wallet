<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Fixtures\Access;

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
}
