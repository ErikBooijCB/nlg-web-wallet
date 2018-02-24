<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Infrastructure\Access;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use GuldenWallet\Backend\Application\Access\AccessToken;
use GuldenWallet\Backend\Application\Access\TokenIdentifier;
use GuldenWallet\Backend\Application\Access\UnableToCreateAccessTokenException;
use GuldenWallet\Backend\Application\Access\UnableToExpireAccessTokenException;
use GuldenWallet\Backend\Application\Helper\SystemClock;
use GuldenWallet\Backend\Domain\Access\InvalidCredentialsException;
use GuldenWallet\Backend\Infrastructure\Access\PdoAccessTokenService;
use GuldenWallet\Tests\Fixtures\Access\AccessTokenFixture;
use PDO;
use PDOException;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @covers \GuldenWallet\Backend\Infrastructure\Access\PdoAccessTokenService
 */
class PdoAccessTokenServiceTest extends TestCase
{
    /** @var PdoAccessTokenService */
    private $accessTokenService;

    /** @var PDO|ObjectProphecy */
    private $pdo;

    /** @var PDOStatement|ObjectProphecy */
    private $statement;

    /** @var SystemClock|ObjectProphecy */
    private $systemClock;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->statement = self::prophesize(PDOStatement::class);
        $this->statement->bindValue(Argument::type('string'), Argument::any(), Argument::type('int'))->willReturn();
        $this->statement->execute()->willReturn();
        $this->statement->fetchAll()->willReturn([
            [
                'SETTING_KEY'   => 'EMAIL',
                'SETTING_VALUE' => 'test@user.com',
            ],
            [
                'SETTING_KEY'   => 'PASSWORD_HASH',
                // Bcrypt hash for 'test'
                'SETTING_VALUE' => '$2y$06$CqO2.e49saON.SxgSdJMm.1N2qNOxfbea0YpcRWn5dLWzsVqlxEYK',
            ],
        ]);

        $this->pdo = self::prophesize(PDO::class);
        $this->pdo->prepare(Argument::type('string'))->willReturn($this->statement);

        $this->systemClock = self::prophesize(SystemClock::class);

        $this->accessTokenService = new PdoAccessTokenService($this->pdo->reveal(), $this->systemClock->reveal());
    }


    /**
     * @return void
     */
    public function test_CreateToken_ShouldThrowException_WhenCredentialsAreInvalid()
    {
        self::expectException(InvalidCredentialsException::class);

        $this->accessTokenService->createToken('test@user.com', 'wrong-password', new DateInterval('P30D'));
    }

    /**
     * @return void
     */
    public function test_CreateToken_ShouldThrowException_WhenUnableToPersistToken()
    {
        self::expectException(UnableToCreateAccessTokenException::class);

        $statement = $this->statement;

        $statement->execute()->will(function () use ($statement) {
            $statement->execute()->willThrow(new PDOException);
        });

        $this->accessTokenService->createToken('test@user.com', 'test', new DateInterval('P30D'));
    }

    /**
     * @return void
     */
    public function test_CreateToken_ShouldReturnNewAccessToken_WhenCredentialsAreValidAndTokenCanBePersisted()
    {
        $accessToken = $this->accessTokenService->createToken('test@user.com', 'test', new DateInterval('P30D'));

        self::assertInstanceOf(AccessToken::class, $accessToken);
    }

    /**
     * @return void
     */
    public function test_ExpireToken_ShouldThrowException_WhenUnableToExpireToken()
    {
        self::expectException(UnableToExpireAccessTokenException::class);

        $token = TokenIdentifier::generate();

        $this->statement->execute()->willThrow(new PDOException);

        $this->accessTokenService->expireToken($token);
    }

    /**
     * @return void
     */
    public function test_ExpireToken_ShouldReturnTrue_WhenExpirationOfTokenIsSuccessful()
    {
        $token = TokenIdentifier::generate();

        $this->statement->bindValue(Argument::type('string'), $token->toString(), PDO::PARAM_STR)->shouldBeCalled();
        $this->statement->execute()->willReturn()->shouldBeCalled();

        $this->accessTokenService->expireToken($token);
    }

    /**
     * @return void
     */
    public function test_ValidateToken_ShouldReturnFalse_WhenTokenHasExpired()
    {
        $accessToken = AccessTokenFixture::tokenIdentifier();

        $this->systemClock->getCurrentDateAndTime()->willReturn(new DateTimeImmutable('2018-02-24 13:58:42'));
        $this->statement->fetch()->willReturn($this->accessTokenFetchResult(
            $accessToken->toString(),
            new DateTimeImmutable('2018-02-20 09:11:27'),
            AccessTokenFixture::randomTokenIdentifier()->toString()
        ));

        self::assertFalse($this->accessTokenService->validateToken($accessToken));
    }

    /**
     * @return void
     */
    public function test_ValidateToken_ShouldReturnFalse_WhenTokenCannotBeFetched()
    {
        $accessToken = AccessTokenFixture::tokenIdentifier();

        $this->statement->execute()->willThrow(new PDOException);

        self::assertFalse($this->accessTokenService->validateToken($accessToken));
    }

    /**
     * @return void
     */
    public function test_ValidateToken_ShouldReturnFalse_WhenTokenDoesNotExist()
    {
        $accessToken = AccessTokenFixture::tokenIdentifier();

        $this->statement->fetch()->willReturn([]);

        self::assertFalse($this->accessTokenService->validateToken($accessToken));
    }

    /**
     * @return void
     */
    public function test_ValidateToken_ShouldReturnTrue_WhenTokenIsValid()
    {
        $accessToken = AccessTokenFixture::tokenIdentifier();

        $this->systemClock->getCurrentDateAndTime()->willReturn(new DateTimeImmutable('2018-02-24 13:58:42'));
        $this->statement->fetch()->willReturn($this->accessTokenFetchResult(
            $accessToken->toString(),
            new DateTimeImmutable('2018-02-28 17:59:48'),
            AccessTokenFixture::randomTokenIdentifier()->toString()
        ));

        self::assertTrue($this->accessTokenService->validateToken($accessToken));
    }

    /**
     * @param string            $accessToken
     * @param DateTimeInterface $expiration
     * @param string            $refreshToken
     *
     * @return array
     */
    private function accessTokenFetchResult(
        string $accessToken,
        DateTimeInterface $expiration,
        string $refreshToken
    ): array {
        return [
            'ACCESS_TOKEN'  => $accessToken,
            'EXPIRATION'    => $expiration->format('Y-m-d H:i:s'),
            'REFRESH_TOKEN' => $refreshToken,
        ];
    }
}
