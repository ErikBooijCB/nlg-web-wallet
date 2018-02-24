<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Helper\Constant;

use PHPUnit\Framework\MockObject\RuntimeException;

/**
 * @codeCoverageIgnore
 */
class AlreadyDefinedConstantException extends RuntimeException
{
    /**
     * @param string $name
     *
     * @return self
     */
    public static function forConstant(string $name): self
    {
        return new static("Constant '{$name}' was already defined and could therefore not be redefined'");
    }
}
