<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Access;

use Exception;

final class TokenIdentifier
{
    /** @var int */
    const HEX_LENGTH = 64;

    /** @var string */
    private $identifier = '';

    /**
     * TokenIdentifier should only be created through its factory methods
     */
    private function __construct()
    {
    }

    /**
     * @param TokenIdentifier $tokenIdentifier
     *
     * @return bool
     */
    public function equals(self $tokenIdentifier): bool
    {
        return $this->identifier === $tokenIdentifier->identifier;
    }

    /**
     * @param string $tokenIdentifier
     *
     * @return self
     * @throws InvalidTokenIdentifierException
     */
    public static function fromString(string $tokenIdentifier): self
    {
        if (preg_match('/^[A-F0-9]*$/i', $tokenIdentifier) === 0) {
            throw InvalidTokenIdentifierException::becauseOfInvalidCharacters($tokenIdentifier);
        }

        if (strlen($tokenIdentifier) !== static::HEX_LENGTH) {
            throw InvalidTokenIdentifierException::becauseOfInvalidLength($tokenIdentifier);
        }

        $identifier = new static;

        $identifier->identifier = hex2bin(strtolower($tokenIdentifier));

        return $identifier;
    }

    /**
     * @return self
     */
    public static function generate(): self
    {
        try {
            $identifier = random_bytes((int)floor(static::HEX_LENGTH / 2));
        // @codeCoverageIgnoreStart
        } catch (Exception $exception) {
            return static::generate();
        }
        // @codeCoverageIgnoreEnd */

        $tokenIdentifier = new static;
        $tokenIdentifier->identifier = $identifier;

        return $tokenIdentifier;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return bin2hex($this->identifier);
    }
}
