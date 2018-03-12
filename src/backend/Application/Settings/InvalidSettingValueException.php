<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Settings;

use RuntimeException;

class InvalidSettingValueException extends RuntimeException
{
    /**
     * @param string $value
     * @return self
     */
    public function forValue(string $value): self
    {
        return new static('Could not deserialize the following stored value: ' . $value);
    }
}
