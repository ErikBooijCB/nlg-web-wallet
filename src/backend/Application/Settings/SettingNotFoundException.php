<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Settings;

use RuntimeException;

class SettingNotFoundException extends RuntimeException
{
    /**
     * @param string $key
     * @return self
     */
    public static function forKey(string $key): self
    {
        return new static("Setting '{$key}' not found.");
    }
}
