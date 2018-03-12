<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Settings;

use RuntimeException;
use Throwable;

class UnableToUpdateSettingException extends RuntimeException
{
    /**
     * @param Throwable $throwable
     * @return self
     */
    public static function fromPrevious(Throwable $throwable): self
    {
        return new static('Could not persist setting.', 0, $throwable);
    }
}
