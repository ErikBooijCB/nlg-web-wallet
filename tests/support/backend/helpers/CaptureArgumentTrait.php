<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Support\Helpers;

use Prophecy\Argument;
use Prophecy\Argument\Token\CallbackToken;

trait CaptureArgumentTrait
{
    /**
     * @param $captured
     * @return CallbackToken
     */
    private function captureArgument(&$captured): CallbackToken
    {
        return Argument::that(function ($argument) use (&$captured): bool {
            $captured = $argument;

            return true;
        });
    }
}
