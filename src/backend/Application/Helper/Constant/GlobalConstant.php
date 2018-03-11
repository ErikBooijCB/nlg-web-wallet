<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Helper\Constant;

class GlobalConstant
{
    /**
     * @param string $constantName
     *
     * @return bool
     */
    public static function isDefined(string $constantName): bool
    {
        return defined($constantName);
    }

    /**
     * @param string $constantName
     *
     * @return mixed
     * @throws UndefinedConstantException
     */
    public static function read(string $constantName)
    {
        if (!defined($constantName)) {
            throw UndefinedConstantException::forConstant($constantName);
        }

        return constant($constantName);
    }

    /**
     * @param string $constantName
     *
     * @return mixed
     */
    public static function readUnsafe(string $constantName)
    {
        try {
            return self::read($constantName);
        } catch (UndefinedConstantException $exception) {
            return null;
        }
    }

    /**
     * @param string $constantName
     * @param mixed $value
     *
     * @return void
     * @throws AlreadyDefinedConstantException
     */
    public static function write(string $constantName, $value)
    {
        if (defined($constantName)) {
            throw AlreadyDefinedConstantException::forConstant($constantName);
        }

        define($constantName, $value);
    }
}
