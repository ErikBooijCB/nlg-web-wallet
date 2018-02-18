<?php
declare(strict_types=1);

namespace GuldenWallet\Tests\Unit\Backend\Infrastructure\Access;

use DateInterval;
use GuldenWallet\Backend\Application\Access\AccessToken;
use GuldenWallet\Backend\Application\Access\UnableToCreateAccessTokenException;
use GuldenWallet\Backend\Domain\Access\InvalidCredentialsException;
use GuldenWallet\Backend\Infrastructure\Access\PdoAccessTokenService;
use PDO;
use PDOException;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class PdoAccessTokenServiceTest extends TestCase
{
    /** @var PdoAccessTokenService */
    private $accessTokenService;

    /** @var PDO|ObjectProphecy */
    private $pdo;

    /** @var PDOStatement|ObjectProphecy */
    private $statement;

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
                'SETTING_KEY' => 'EMAIL',
                'SETTING_VALUE' => 'test@user.com',
            ],
            [
                'SETTING_KEY' => 'PASSWORD_HASH',
                // Bcrypt hash for 'test'
                'SETTING_VALUE' => '$2y$06$CqO2.e49saON.SxgSdJMm.1N2qNOxfbea0YpcRWn5dLWzsVqlxEYK',
            ]
        ]);

        $this->pdo = self::prophesize(PDO::class);
        $this->pdo->prepare(Argument::type('string'))->willReturn($this->statement);

        $this->accessTokenService = new PdoAccessTokenService($this->pdo->reveal());
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
}
