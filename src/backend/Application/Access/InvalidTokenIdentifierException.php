<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Access;

class InvalidTokenIdentifierException extends \Exception
{
    /**
     * @param string $identifier
     *
     * @return self
     */
    public static function becauseOfInvalidCharacters(string $identifier): self
    {
        return new static(
            "'{$identifier}' cannot be used as a token identifier because it contains illegal characters."
        );
    }

    /**
     * @param string $identifier
     *
     * @return InvalidTokenIdentifierException
     */
    public static function becauseOfInvalidLength(string $identifier): self
    {
        return new static(
            sprintf(
                "'%s' cannot be used as a token identifier because it's length " .
                  "of %d characters does not meet the specification",
                $identifier,
                strlen($identifier)
            )
        );
    }
}
