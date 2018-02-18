<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Infrastructure\Controller;

use DateInterval;
use DateTime;
use GuldenWallet\Backend\Application\Access\AccessTokenServiceInterface;
use GuldenWallet\Backend\Application\Access\UnableToCreateAccessTokenException;
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
            'refreshToken' => $accessToken->getRefreshToken()->getTokenIdentifier()->toString(),
        ], 201);
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        if (($token = $request->getAttribute('token', '')) === '') {
            return ResponseFactory::failure('no access token provided', 400);
        }

        return ResponseFactory::success([]);
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
