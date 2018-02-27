<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Controller;

use DateInterval;
use DateTime;
use GuldenWallet\Backend\Application\Access\AccessTokenNotFoundException;
use GuldenWallet\Backend\Application\Access\AccessTokenServiceInterface;
use GuldenWallet\Backend\Application\Access\InvalidRefreshTokenException;
use GuldenWallet\Backend\Application\Access\InvalidTokenIdentifierException;
use GuldenWallet\Backend\Application\Access\TokenIdentifier;
use GuldenWallet\Backend\Application\Access\UnableToCreateAccessTokenException;
use GuldenWallet\Backend\Application\Access\UnableToExpireAccessTokenException;
use GuldenWallet\Backend\Application\Access\UnableToRefreshTokenException;
use GuldenWallet\Backend\Application\Access\UnableToRetrieveAccessTokenException;
use GuldenWallet\Backend\Application\Helper\ResponseFactory;
use GuldenWallet\Backend\Domain\Access\InvalidCredentialsException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AccessTokenHttpController
{
    /** @var AccessTokenServiceInterface */
    private $accessTokenService;

    /**
     * @param AccessTokenServiceInterface $accessTokenService
     */
    public function __construct(AccessTokenServiceInterface $accessTokenService)
    {
        $this->accessTokenService = $accessTokenService;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $requestBody = (array)$request->getParsedBody();

        if (($checkResult = $this->checkPrerequisitesForCreatingToken($requestBody)) instanceof ResponseInterface) {
            return $checkResult;
        }

        try {
            $accessToken = $this->accessTokenService->createToken(
                $requestBody['email'],
                $requestBody['password'],
                new DateInterval('P30D')
            );
        } catch (InvalidCredentialsException $exception) {
            return ResponseFactory::failure('the provided credentials were invalid', 401);
        } catch (UnableToCreateAccessTokenException $exception) {
            return ResponseFactory::failure('unable to create access token for technical reasons', 500);
        }

        return ResponseFactory::success([
            'accessToken'  => $accessToken->getTokenIdentifier()->toString(),
            'expires'      => $accessToken->getExpires()->format(DateTime::ATOM),
            'refreshToken' => $accessToken->getRefreshTokenIdentifier()->toString(),
        ], 201);
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function refresh(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $accessTokenIdentifier = TokenIdentifier::fromString($request->getAttribute('token', ''));
        } catch (InvalidTokenIdentifierException $exception) {
            return ResponseFactory::failure('invalid access token provided', 400);
        }

        try {
            $requestBody = (array)$request->getParsedBody();
            $refreshToken = TokenIdentifier::fromString($requestBody['refresh'] ?? '');

            $newAccessToken = $this->accessTokenService->refreshToken($accessTokenIdentifier, $refreshToken);

            return ResponseFactory::success([
                'accessToken'  => $newAccessToken->getTokenIdentifier()->toString(),
                'expires'      => $newAccessToken->getExpires()->format(DateTime::ATOM),
                'refreshToken' => $newAccessToken->getRefreshTokenIdentifier()->toString(),
            ], 201);
        } catch (InvalidTokenIdentifierException $exception) {
            list ($errorMessage, $statusCode) = ['refresh token not (correctly) provided', 400];
        } catch (AccessTokenNotFoundException $exception) {
            list ($errorMessage, $statusCode) = ['access token not found', 404];
        } catch (InvalidRefreshTokenException $exception) {
            list ($errorMessage, $statusCode) = ['the provided refresh token was invalid for the access token', 401];
        } catch (UnableToRefreshTokenException $exception) {
            list ($errorMessage, $statusCode) = ['the token could not be refreshed', 500];
        }

        return ResponseFactory::failure($errorMessage ?? '', $statusCode ?? 400);
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function retrieve(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $accessTokenIdentifier = TokenIdentifier::fromString($request->getAttribute('token', ''));

            $accessToken = $this->accessTokenService->getAccessTokenByIdentifier($accessTokenIdentifier);
        } catch (InvalidTokenIdentifierException $exception) {
            return ResponseFactory::failure('the provided token was not a valid access token', 400);
        } catch (UnableToRetrieveAccessTokenException $exception) {
            return ResponseFactory::failure('the access token could not be retrieved', 500);
        } catch (AccessTokenNotFoundException $exception) {
            return ResponseFactory::failure('the provided token does not exist', 404);
        }

        return ResponseFactory::success([
            'expires' => $accessToken->getExpires()->format(DateTime::ATOM),
        ]);
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function revoke(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $this->accessTokenService->expireToken(TokenIdentifier::fromString($request->getAttribute('token', '')));
        } catch (InvalidTokenIdentifierException $exception) {
            return ResponseFactory::failure('the provided token was not a valid access token', 400);
        } catch (UnableToExpireAccessTokenException $exception) {
            return ResponseFactory::failure('unable to expire access token for technical reasons', 500);
        }

        return ResponseFactory::successMessage('the provided token has been expired', 204);
    }

    /**
     * @param array $requestBody
     *
     * @return ResponseInterface|null
     */
    private function checkPrerequisitesForCreatingToken(array $requestBody)
    {
        if (empty($requestBody['email'] ?? '')) {
            return ResponseFactory::failure('e-mail address missing from request', 400);
        }

        if (empty($password = $requestBody['password'] ?? '')) {
            return ResponseFactory::failure('password missing from request', 400);
        }

        return null;
    }
}
