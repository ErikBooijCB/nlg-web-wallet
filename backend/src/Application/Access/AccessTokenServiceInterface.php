<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Access;

use DateInterval;
use GuldenWallet\Backend\Domain\Access\InvalidCredentialsException;

interface AccessTokenServiceInterface
{
    /**
     * @param string       $emailAddress
     * @param string       $password
     * @param DateInterval $validity
     *
     * @return mixed
     * @throws InvalidCredentialsException
     * @throws UnableToCreateAccessTokenException
     */
    public function createToken(string $emailAddress, string $password, DateInterval $validity): AccessToken;

    /**
     * @param UserProvidedAccessToken $accessToken
     *
     * @return void
     * @throws UnableToExpireAccessTokenException
     */
    public function expireToken(UserProvidedAccessToken $accessToken);

    /**
     * @param UserProvidedRefreshToken $refreshToken
     *
     * @return AccessToken
     */
    public function refreshToken(UserProvidedRefreshToken $refreshToken): AccessToken;

    /**
     * @param UserProvidedAccessToken $accessToken
     *
     * @return bool
     */
    public function validateToken(UserProvidedAccessToken $accessToken): bool;
}
