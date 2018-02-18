<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Access\Statement;

use GuldenWallet\Backend\Application\Access\TokenIdentifier;
use GuldenWallet\Backend\Infrastructure\Database\PreparableStatement;

class ExpireAccessTokenStatement implements PreparableStatement
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
     * @return array
     */
    public function getParameters(): array
    {
        return [
            ':token' => $this->tokenIdentifier->toString(),
        ];
    }

    /**
     * @return string
     */
    public function getStatement(): string
    {
        return '
            DELETE FROM access_tokens WHERE ACCESS_TOKEN = :token;
        ';
    }
}
