<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Settings;

use RuntimeException;
use Throwable;

class UnableToRetrieveSettingException extends RuntimeException
{
    /**
     * @param Throwable $throwable
     * @return self
     */
    public static function fromPrevious(Throwable $throwable): self
    {
        return new static('Could not retrieve setting value.', 0, $throwable);
    }
}
