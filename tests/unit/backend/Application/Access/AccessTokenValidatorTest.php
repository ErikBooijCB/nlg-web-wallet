<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Application\Access;

use DateTimeImmutable;
use GuldenWallet\Backend\Application\Access\AccessToken;
use GuldenWallet\Backend\Application\Access\AccessTokenNotFoundException;
use GuldenWallet\Backend\Application\Access\AccessTokenServiceInterface;
use GuldenWallet\Backend\Application\Access\AccessTokenValidator;
use GuldenWallet\Backend\Application\Access\TokenIdentifier;
use GuldenWallet\Backend\Application\Access\UnableToRetrieveAccessTokenException;
use GuldenWallet\Backend\Application\Helper\SystemClock;
use GuldenWallet\Tests\Support\Fixtures\Access\AccessTokenFixture;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @covers \GuldenWallet\Backend\Application\Access\AccessTokenValidator
 */
class AccessTokenValidatorTest extends TestCase
{
    /** @var AccessTokenServiceInterface|ObjectProphecy */
    private $accessTokenService;

    /** @var SystemClock */
    private $systemClock;

    /** @var AccessTokenValidator */
    private $validator;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->accessTokenService = self::prophesize(AccessTokenServiceInterface::class);
        $this->systemClock = self::prophesize(SystemClock::class);

        $this->validator = new AccessTokenValidator($this->accessTokenService->reveal(), $this->systemClock->reveal());
    }

    /**
     * @return void
     */
    public function test_ValidateShouldReturnTrue_WhenAccessTokenExistsAndExpiresInTheFuture()
    {
        $accessTokenIdentifier = AccessTokenFixture::randomTokenIdentifier();
        $expiration = new DateTimeImmutable('2018-02-28 16:23:53');
        $refreshTokenIdentifier = AccessTokenFixture::randomTokenIdentifier();

        $this->accessTokenService->getAccessTokenByIdentifier($accessTokenIdentifier)
            ->willReturn(new AccessToken($accessTokenIdentifier, $expiration, $refreshTokenIdentifier));
        $this->systemClock->getCurrentDateAndTime()
            ->willReturn(new DateTimeImmutable('2018-02-26 12:10:04'));

        self::assertTrue($this->validator->validate($accessTokenIdentifier));
    }

    /**
     * @return void
     */
    public function test_ValidateShouldReturnFalse_WhenAccessTokenExistsAndButHasExpired()
    {
        $accessTokenIdentifier = AccessTokenFixture::randomTokenIdentifier();
        $expiration = new DateTimeImmutable('2018-02-26 12:10:04');
        $refreshTokenIdentifier = AccessTokenFixture::randomTokenIdentifier();

        $this->accessTokenService->getAccessTokenByIdentifier($accessTokenIdentifier)
            ->willReturn(new AccessToken($accessTokenIdentifier, $expiration, $refreshTokenIdentifier));
        $this->systemClock->getCurrentDateAndTime()
            ->willReturn(new DateTimeImmutable('2018-02-28 16:23:53'));

        self::assertFalse($this->validator->validate($accessTokenIdentifier));
    }

    /**
     * @return void
     */
    public function test_ValidateShouldReturnFalse_WhenAccessTokenCannotBeRetrieved()
    {
        $this->accessTokenService->getAccessTokenByIdentifier(Argument::type(TokenIdentifier::class))
            ->willThrow(new UnableToRetrieveAccessTokenException);

        self::assertFalse($this->validator->validate(AccessTokenFixture::tokenIdentifier()));
    }

    /**
     * @return void
     */
    public function test_ValidateShouldReturnFalse_WhenAccessTokenDoesNotExist()
    {
        $this->accessTokenService->getAccessTokenByIdentifier(Argument::type(TokenIdentifier::class))
            ->willThrow(new AccessTokenNotFoundException());

        self::assertFalse($this->validator->validate(AccessTokenFixture::tokenIdentifier()));
    }
}
