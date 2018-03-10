<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Support\Fixtures\Node;

use GuldenWallet\Backend\Domain\Node\NodeOverview;
use GuldenWallet\Backend\Domain\Node\VersionMapper;

class NodeOverviewFixture
{
    /**
     * @param array $arguments
     * @return NodeOverview
     */
    public static function standard(...$arguments): NodeOverview
    {
        $arguments = self::raw(...$arguments);

        $arguments['version'] = VersionMapper::asString($arguments['version']);

        return new NodeOverview(...array_values($arguments));
    }

    /**
     * @param int $versionNumber
     * @param int $walletVersion
     * @param int $protocolVersion
     * @param int $connections
     * @param int $blocks
     * @param float $difficulty
     * @param float $balance
     * @param int $keyPoolSize
     * @param bool $testnet
     * @return array
     */
    public static function raw(
        int $versionNumber = 1060410,
        int $walletVersion = 60000,
        int $protocolVersion = 70014,
        int $connections = 45,
        int $blocks = 654321,
        float $difficulty = 0.123456789,
        float $balance = 15000,
        int $keyPoolSize = 42,
        bool $testnet = false
    ): array {
        return [
            'version' => $versionNumber,
            'walletversion' => $walletVersion,
            'protocolversion' => $protocolVersion,
            'connections' => $connections,
            'blocks' => $blocks,
            'difficulty' => $difficulty,
            'balance' => $balance,
            'keypoolsize' => $keyPoolSize,
            'testnet' => $testnet,
        ];
    }
}
