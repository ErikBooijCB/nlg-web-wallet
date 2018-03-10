<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Domain\Node;

class VersionMapper
{
    /**
     * @param int $version
     * @return string
     */
    public static function asString(int $version): string
    {
        $majorFactor = 1000000;
        $minorFactor = 10000;
        $patchFactor = 100;

        $major = (int)floor($version / $majorFactor);
        $minor = (int)floor(($version = $version - $major * $majorFactor) / $minorFactor);
        $patch = (int)floor(($version = $version - $minor * $minorFactor) / $patchFactor);
        $build = (int)floor($version % $patchFactor);

        return "{$major}.{$minor}.{$patch}.{$build}";
    }
}
