<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Helper;

use DateTimeImmutable;
use DateTimeInterface;

class SystemClock
{
    /**
     * @return DateTimeInterface
     *
     * @codeCoverageIgnore
     */
    public function getCurrentDateAndTime(): DateTimeInterface
    {
        return new DateTimeImmutable;
    }
}
