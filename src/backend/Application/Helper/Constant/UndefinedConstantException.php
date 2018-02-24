<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Helper\Constant;

use RuntimeException;

/**
 * @codeCoverageIgnore
 */
class UndefinedConstantException extends RuntimeException
{
    /**
     * @param string $name
     *
     * @return self
     */
    public static function forConstant(string $name): self
    {
        return new static("Constant '{$name}' was not defined'");
    }
}
