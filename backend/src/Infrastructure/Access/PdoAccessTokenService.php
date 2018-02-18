<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Access;

use DateInterval;
use DateTimeImmutable;
use Exception;
use GuldenWallet\Backend\Application\Access\AccessTokenServiceInterface;
use GuldenWallet\Backend\Application\Access\UnableToCreateAccessTokenException;
use GuldenWallet\Backend\Application\Access\UserProvidedAccessToken;
use GuldenWallet\Backend\Application\Access\UserProvidedRefreshToken;
use GuldenWallet\Backend\Domain\Access\InvalidCredentialsException;
use GuldenWallet\Backend\Domain\Access\PersistedAccessToken;
use GuldenWallet\Backend\Domain\Access\PersistedRefreshToken;
use GuldenWallet\Backend\Domain\Access\TokenIdentifier;
use GuldenWallet\Backend\Infrastructure\Database\Prepare;
use PDO;

class PdoAccessTokenService implements AccessTokenServiceInterface
{
    /** @var PDO */
    private $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @inheritdoc
     */
    public function createToken(string $emailAddress, string $password, DateInterval $validity): PersistedAccessToken
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

            return new PersistedAccessToken(
                $accessToken,
                $expiration,
                new PersistedRefreshToken(
                    $refreshToken
                )
            );
        } catch (Exception $exception) {
            throw UnableToCreateAccessTokenException::fromPrevious($exception);
        }
    }


    /**
     * @param UserProvidedAccessToken $accessToken
     *
     * @return bool
     */
    public function expireToken(UserProvidedAccessToken $accessToken): bool
    {
        // TODO: Implement expireToken() method.

        return false;
    }

    /**
     * @param UserProvidedRefreshToken $refreshToken
     *
     * @return PersistedAccessToken
     */
    public function refreshToken(UserProvidedRefreshToken $refreshToken): PersistedAccessToken
    {
        // TODO: Implement refreshToken() method.

        return new PersistedAccessToken(
            TokenIdentifier::generate(),
            new DateTimeImmutable,
            new PersistedRefreshToken(
                TokenIdentifier::generate()
            )
        );
    }

    /**
     * @param UserProvidedAccessToken $accessToken
     *
     * @return bool
     */
    public function validateToken(UserProvidedAccessToken $accessToken): bool
    {
        // TODO: Implement validateToken() method.

        return false;
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
        }, ['email' => null, 'password_hash' => null]);

        return $credentials['email'] === $emailAddress && password_verify($password, $credentials['password_hash']);
    }
}
