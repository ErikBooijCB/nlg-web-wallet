<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Access\Statement;

use DateTimeInterface;
use GuldenWallet\Backend\Domain\Access\TokenIdentifier;
use GuldenWallet\Backend\Infrastructure\Database\PreparableStatement;
use PDO;

class PersistNewTokenStatement implements PreparableStatement
{
    /** @var TokenIdentifier */
    private $accessToken;

    /** @var DateTimeInterface */
    private $expiration;

    /** @var TokenIdentifier */
    private $refreshToken;

    /**
     * @param TokenIdentifier   $accessToken
     * @param DateTimeInterface $expiration
     * @param TokenIdentifier   $refreshToken
     */
    public function __construct(
        TokenIdentifier $accessToken,
        DateTimeInterface $expiration,
        TokenIdentifier $refreshToken
    ) {
        $this->accessToken = $accessToken;
        $this->expiration = $expiration;
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return [
            ':accessToken' => [$this->accessToken->toString(), PDO::PARAM_STR],
            ':expiration' => [$this->expiration->format('Y-m-d H:i:s'), PDO::PARAM_STR],
            ':refreshToken' => [$this->refreshToken->toString(), PDO::PARAM_STR],
        ];
    }

    /**
     * @return string
     */
    public function getStatement(): string
    {
        return '
            INSERT INTO
              access_tokens
            (ACCESS_TOKEN, EXPIRATION, REFRESH_TOKEN)
              VALUES
            (:accessToken, :expiration, :refreshToken)
        ';
    }
}
