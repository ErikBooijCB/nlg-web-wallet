<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Domain\Node;

use GuldenWallet\Backend\Domain\Node\VersionMapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GuldenWallet\Backend\Domain\Node\VersionMapper
 */
class VersionMapperTest extends TestCase
{
    /**
     * @param int $versionInput
     * @param string $expectedOutput
     *
     * @dataProvider mapperDataProvider
     */
    public function testMapsCorrectly(int $versionInput, string $expectedOutput)
    {
        self::assertEquals($expectedOutput, VersionMapper::asString($versionInput));
    }

    /**
     * @return array
     */
    public function mapperDataProvider(): array
    {
        return [
            [1060410, '1.6.4.10'],
            [1, '0.0.0.1'],
            [105, '0.0.1.5'],
            [40525, '0.4.5.25'],
            [1081972, '1.8.19.72'],
        ];
    }
}
