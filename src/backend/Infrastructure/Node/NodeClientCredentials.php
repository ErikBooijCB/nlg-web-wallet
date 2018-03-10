<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Node;

/**
 * @codeCoverageIgnore
 */
class NodeClientCredentials
{
    /** @var string */
    private $host;

    /** @var int */
    private $port;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /**
     * @param string $host
     * @param int $port
     * @param string $username
     * @param string $password
     */
    public function __construct(string $host, int $port, string $username, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
