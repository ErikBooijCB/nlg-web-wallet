<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Domain\Node;

/**
 * @codeCoverageIgnore
 */
class NodeOverview
{
    /** @var float */
    private $balance;

    /** @var int */
    private $blocks;

    /** @var int */
    private $connections;

    /** @var float */
    private $difficulty;

    /** @var int */
    private $keyPoolSize;

    /** @var int */
    private $protocolVersion;

    /** @var bool */
    private $testnet;

    /** @var string */
    private $version;

    /** @var int */
    private $walletVersion;

    /**
     * @param string $version
     * @param int $walletVersion
     * @param int $protocolVersion
     * @param int $connections
     * @param int $blocks
     * @param float $difficulty
     * @param float $balance
     * @param int $keyPoolSize
     * @param bool $testnet
     */
    public function __construct(
        string $version,
        int $walletVersion,
        int $protocolVersion,
        int $connections,
        int $blocks,
        float $difficulty,
        float $balance,
        int $keyPoolSize,
        bool $testnet = false
    ) {
        $this->version = $version;
        $this->walletVersion = $walletVersion;
        $this->protocolVersion = $protocolVersion;
        $this->connections = $connections;
        $this->blocks = $blocks;
        $this->difficulty = $difficulty;
        $this->balance = $balance;
        $this->keyPoolSize = $keyPoolSize;
        $this->testnet = $testnet;
    }

    /**
     * @param string $argument1
     * @param string $argument2
     * @param string $argument3
     * @param string $argument4
     * @return string
     */
    public function doSomethingWithAFewArguments(
        string $argument1,
        string $argument2,
        string $argument3,
        string $argument4
    ): string {
        return "{$argument1}.{$argument2}.{$argument3}.{$argument4}";
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * @return int
     */
    public function getBlocks(): int
    {
        return $this->blocks;
    }

    /**
     * @return int
     */
    public function getConnections(): int
    {
        return $this->connections;
    }

    /**
     * @return float
     */
    public function getDifficulty(): float
    {
        return $this->difficulty;
    }

    /**
     * @return int
     */
    public function getKeyPoolSize(): int
    {
        return $this->keyPoolSize;
    }

    /**
     * @return int
     */
    public function getProtocolVersion(): int
    {
        return $this->protocolVersion;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return int
     */
    public function getWalletVersion(): int
    {
        return $this->walletVersion;
    }

    /**
     * @return bool
     */
    public function isHealthy(): bool
    {
        return $this->connections >= 6;
    }

    /**
     * @return bool
     */
    public function isTestnet(): bool
    {
        return $this->testnet;
    }
}
