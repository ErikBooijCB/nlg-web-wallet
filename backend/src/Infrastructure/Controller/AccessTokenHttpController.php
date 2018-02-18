<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Controller;

use DateInterval;
use DateTime;
use GuldenWallet\Backend\Application\Access\AccessTokenServiceInterface;
use GuldenWallet\Backend\Application\Access\InvalidTokenIdentifierException;
use GuldenWallet\Backend\Application\Access\TokenIdentifier;
use GuldenWallet\Backend\Application\Access\UnableToCreateAccessTokenException;
use GuldenWallet\Backend\Application\Access\UnableToExpireAccessTokenException;
use GuldenWallet\Backend\Application\Access\UserProvidedAccessToken;
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
    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $token = TokenIdentifier::fromString($request->getAttribute('token'));

            $this->accessTokenService->expireToken(new UserProvidedAccessToken($token));

            return ResponseFactory::successMessage('the provided token has been expired', 204);
        } catch (InvalidTokenIdentifierException $exception) {
            return ResponseFactory::failure('the provided token was not a valid access token', 400);
        } catch (UnableToExpireAccessTokenException $exception) {
            return ResponseFactory::failure('unable to expire access token for technical reasons', 500);
        }
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function post(ServerRequestInterface $request): ResponseInterface
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
            'refreshToken' => $accessToken->getRefreshToken()->toString(),
        ], 201);
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
