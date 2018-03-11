<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Domain\Node;

use GuldenWallet\Backend\Domain\Node\NodeOverview;
use GuldenWallet\Backend\Domain\Node\VersionMapper;
use GuldenWallet\Tests\Support\Fixtures\Node\NodeOverviewFixture;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GuldenWallet\Backend\Domain\Node\NodeOverview
 */
class NodeOverviewTest extends TestCase
{
    /**
     * @param int $numberOfConnections
     * @param bool $expectedToBeHealthy
     *
     * @dataProvider isHealthyDataProvider
     */
    public function test_IsHealthy_ShouldReturnTrue_WhenNodeIsHealthyAccordingToDefinition(
        int $numberOfConnections,
        bool $expectedToBeHealthy
    ) {
        $baseOverview = NodeOverviewFixture::raw();

        $baseOverview['version'] = VersionMapper::asString($baseOverview['version']);
        $baseOverview['connections'] = $numberOfConnections;

        $nodeOverview = new NodeOverview(...array_values($baseOverview));

        self::assertEquals($expectedToBeHealthy, $nodeOverview->isHealthy());
    }

    /**
     * @return array
     */
    public function isHealthyDataProvider(): array
    {
        return [
            [0, false],
            [5, false],
            [6, true],
            [80, true],
        ];
    }
}
