<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Domain\Access;

use GuldenWallet\Backend\Application\Access\InvalidTokenIdentifierException;
use GuldenWallet\Backend\Application\Access\TokenIdentifier;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GuldenWallet\Backend\Application\Access\TokenIdentifier
 */
class TokenIdentifierTest extends TestCase
{
    /**
     * @param string $candidate
     *
     * @testWith ["abcdef"]
     *           ["0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0"]
     *           ["0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdez"]
     */
    public function test_FromString_ShouldThrowException_WhenIdentifierDoesNotMeetSpecification(string $candidate) {
        self::expectException(InvalidTokenIdentifierException::class);

        TokenIdentifier::fromString($candidate);
    }

    /**
     * @param string $candidate
     *
     * @testWith ["0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef"]
     *           ["0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF"]
     */
    public function test_FromString_ShouldCreateInstance_WhenIdentifierIsValid(string $candidate)
    {
        self::assertInstanceOf(TokenIdentifier::class, TokenIdentifier::fromString($candidate));
    }

    /**
     * @return void
     */
    public function test_ToString_ShouldRecreateHexRepresentation_WhenCalled()
    {
        $hexIdentifier = '0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef';

        $tokenIdentifier = TokenIdentifier::fromString($hexIdentifier);

        self::assertEquals($hexIdentifier, $tokenIdentifier->toString());
    }

    /**
     * @return void
     */
    public function test_Equals_ShouldReturnTrue_WhenIdentifiersAreEqual()
    {
        $identifierA = '0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef';
        $identifierB = '0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef';

        $tokenIdentifierA = TokenIdentifier::fromString($identifierA);
        $tokenIdentifierB = TokenIdentifier::fromString($identifierB);

        self::assertTrue($tokenIdentifierA->equals($tokenIdentifierB));
    }

    /**
     * @return void
     */
    public function test_Equals_ShouldReturnFalse_WhenIdentifiersAreNotEqual()
    {
        $identifierA = '0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef';
        $identifierB = '0000000000000000000000000000000000000000000000000000000000000000';

        $tokenIdentifierA = TokenIdentifier::fromString($identifierA);
        $tokenIdentifierB = TokenIdentifier::fromString($identifierB);

        self::assertFalse($tokenIdentifierA->equals($tokenIdentifierB));
    }

    /**
     * @return void
     */
    public function test_Generate_ShouldCreateRandomInstanceOfTokenIdentifier()
    {
        $tokenIdentifierA = TokenIdentifier::generate();
        $tokenIdentifierB = TokenIdentifier::generate();

        self::assertInstanceOf(TokenIdentifier::class, $tokenIdentifierA);
        self::assertInstanceOf(TokenIdentifier::class, $tokenIdentifierB);
        self::assertFalse($tokenIdentifierA->equals($tokenIdentifierB));
    }
}
