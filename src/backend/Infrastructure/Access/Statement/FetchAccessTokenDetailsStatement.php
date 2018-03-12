<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Access\Statement;

use GuldenWallet\Backend\Application\Access\TokenIdentifier;
use GuldenWallet\Backend\Infrastructure\Database\PreparableStatement;

/**
 * @codeCoverageIgnore
 */
class FetchAccessTokenDetailsStatement implements PreparableStatement
{
    /** @var string */
    private $tokenIdentifier;

    /**
     * @param TokenIdentifier $tokenIdentifier
     */
    public function __construct(TokenIdentifier $tokenIdentifier)
    {
        $this->tokenIdentifier = $tokenIdentifier->toString();
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return [
            ':tokenIdentifier' => $this->tokenIdentifier
        ];
    }

    /**
     * @return string
     */
    public function getStatement(): string
    {
        return '
            SELECT
              ACCESS_TOKEN,
              EXPIRATION,
              REFRESH_TOKEN
            FROM
              access_tokens
            WHERE
              ACCESS_TOKEN = :tokenIdentifier AND
              EXPIRATION > NOW()
        ';
    }
}
