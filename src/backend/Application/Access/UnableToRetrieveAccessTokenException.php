<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Access;

use Exception;
use Throwable;

class UnableToRetrieveAccessTokenException extends Exception
{
    /**
     * @param Throwable $throwable
     *
     * @return self
     */
    public static function fromPrevious(Throwable $throwable): self
    {
        return new static('Access token details could not be retrieved', 0, $throwable);
    }
}
