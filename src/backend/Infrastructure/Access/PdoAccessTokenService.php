<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Access;

use DateInterval;
use DateTimeImmutable;
use Exception;
use GuldenWallet\Backend\Application\Access\AccessToken;
use GuldenWallet\Backend\Application\Access\AccessTokenServiceInterface;
use GuldenWallet\Backend\Application\Access\TokenIdentifier;
use GuldenWallet\Backend\Application\Access\UnableToCreateAccessTokenException;
use GuldenWallet\Backend\Application\Access\UnableToExpireAccessTokenException;
use GuldenWallet\Backend\Application\Helper\SystemClock;
use GuldenWallet\Backend\Domain\Access\InvalidCredentialsException;
use GuldenWallet\Backend\Infrastructure\Access\Statement\ExpireAccessTokenStatement;
use GuldenWallet\Backend\Infrastructure\Access\Statement\FetchAccessTokenDetailsStatement;
use GuldenWallet\Backend\Infrastructure\Access\Statement\FetchCredentialsStatement;
use GuldenWallet\Backend\Infrastructure\Access\Statement\PersistNewTokenStatement;
use GuldenWallet\Backend\Infrastructure\Database\Prepare;
use PDO;

class PdoAccessTokenService implements AccessTokenServiceInterface
{
    /** @var PDO */
    private $pdo;

    /** @var SystemClock */
    private $systemClock;

    /**
     * @param PDO         $pdo
     * @param SystemClock $systemClock
     */
    public function __construct(PDO $pdo, SystemClock $systemClock)
    {
        $this->pdo = $pdo;
        $this->systemClock = $systemClock;
    }

    /**
     * @inheritdoc
     */
    public function createToken(string $emailAddress, string $password, DateInterval $validity): AccessToken
    {
        if (!$this->verifyCredentials($emailAddress, $password)) {
            throw new InvalidCredentialsException;
        }

        $accessToken = TokenIdentifier::generate();
        $expiration = (new DateTimeImmutable)->add($validity);
        $refreshToken = TokenIdentifier::generate();

        try {
            $statement = Prepare::statement($this->pdo, new PersistNewTokenStatement(
                $accessToken,
                $expiration,
                $refreshToken
            ));

            $statement->execute();

            return new AccessToken($accessToken, $expiration, $refreshToken);
        } catch (Exception $exception) {
            throw UnableToCreateAccessTokenException::fromPrevious($exception);
        }
    }


    /**
     * @inheritdoc
     */
    public function expireToken(TokenIdentifier $accessToken)
    {
        try {
            $statement = Prepare::statement(
                $this->pdo,
                new ExpireAccessTokenStatement($accessToken)
            );

            $statement->execute();
        } catch (Exception $exception) {
            throw UnableToExpireAccessTokenException::fromPrevious($exception);
        }
    }

    /**
     * @inheritdoc
     *
     * @codeCoverageIgnore until implementation is in place
     */
    public function refreshToken(TokenIdentifier $refreshToken): AccessToken
    {
        // TODO: Implement refreshToken() method.

        return new AccessToken(
            TokenIdentifier::generate(),
            new DateTimeImmutable,
            TokenIdentifier::generate()
        );
    }

    /**
     * @inheritdoc
     */
    public function validateToken(TokenIdentifier $accessToken): bool
    {
        try {
            $statement = Prepare::statement($this->pdo, new FetchAccessTokenDetailsStatement($accessToken));

            $statement->execute();

            $accessTokenData = $statement->fetch();

            if (empty($accessTokenData)) return false;

            $expiration = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $accessTokenData['EXPIRATION']);

            return $expiration >= $this->systemClock->getCurrentDateAndTime();
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param string $emailAddress
     * @param string $password
     *
     * @return bool
     */
    private function verifyCredentials(string $emailAddress, string $password): bool
    {
        $statement = Prepare::statement($this->pdo, new FetchCredentialsStatement);

        $statement->execute();

        $result = $statement->fetchAll();

        $credentials = array_reduce($result, function (array $carry, array $row): array {
            $carry[strtolower($row['SETTING_KEY'])] = $row['SETTING_VALUE'];

            return $carry;
        }, ['email' => null, 'password_hash' => '']);

        return $credentials['email'] === $emailAddress && password_verify($password, $credentials['password_hash']);
    }
}
