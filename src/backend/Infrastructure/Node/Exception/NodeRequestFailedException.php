<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Node\Exception;

use GuldenWallet\Backend\Application\Node\NodeRequestFailedExceptionInterface;
use RuntimeException;

class NodeRequestFailedException extends RuntimeException implements NodeRequestFailedExceptionInterface
{
    public static function withMessage(string $message): self
    {
        return new static("The request to the Gulden node failed for the following reason: " . $message);
    }
}
