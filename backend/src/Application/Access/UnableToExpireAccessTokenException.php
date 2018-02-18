<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Access;

use RuntimeException;
use Throwable;

class UnableToExpireAccessTokenException extends RuntimeException
{
    /**
     * @param Throwable $previous
     *
     * @return self
     */
    public static function fromPrevious(Throwable $previous): self
    {
        return new static('Could not expire access token', 0, $previous);
    }
}
