<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Access;

use DateInterval;
use GuldenWallet\Backend\Domain\Access\InvalidCredentialsException;

interface AccessTokenServiceInterface
{
    /**
     * @param string $emailAddress
     * @param string $password
     * @param DateInterval $validity
     *
     * @return mixed
     * @throws InvalidCredentialsException
     * @throws UnableToCreateAccessTokenException
     */
    public function createToken(string $emailAddress, string $password, DateInterval $validity): AccessToken;

    /**
     * @param TokenIdentifier $accessToken
     *
     * @return void
     * @throws UnableToExpireAccessTokenException
     */
    public function expireToken(TokenIdentifier $accessToken);

    /**
     * @param TokenIdentifier $accessToken
     *
     * @return AccessToken
     * @throws AccessTokenNotFoundException
     * @throws UnableToRetrieveAccessTokenException
     */
    public function getAccessTokenByIdentifier(TokenIdentifier $accessToken): AccessToken;

    /**
     * @param TokenIdentifier $accessToken
     * @param TokenIdentifier $refreshToken
     *
     * @return AccessToken
     * @throws AccessTokenNotFoundException
     * @throws InvalidRefreshTokenException
     * @throws UnableToRefreshTokenException
     */
    public function refreshToken(TokenIdentifier $accessToken, TokenIdentifier $refreshToken): AccessToken;
}
