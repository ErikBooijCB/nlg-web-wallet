<?php
declare(strict_types=1);

namespace GuldenWallet\Backend\Application\Access;

use GuldenWallet\Backend\Application\Helper\SystemClock;

class AccessTokenValidator
{
    /** @var AccessTokenServiceInterface */
    private $accessTokenService;

    /** @var SystemClock */
    private $systemClock;

    /**
     * @param AccessTokenServiceInterface $accessTokenService
     * @param SystemClock                 $systemClock
     */
    public function __construct(AccessTokenServiceInterface $accessTokenService, SystemClock $systemClock)
    {
        $this->accessTokenService = $accessTokenService;
        $this->systemClock = $systemClock;
    }

    /**
     * @param TokenIdentifier $accessToken
     *
     * @return bool
     */
    public function validate(TokenIdentifier $accessToken): bool
    {
        try {
            $accessToken = $this->accessTokenService->getAccessTokenByIdentifier($accessToken);

            return $accessToken->getExpires() >= $this->systemClock->getCurrentDateAndTime();
        } catch (AccessTokenNotFoundException $exception) {
            return false;
        } catch (UnableToRetrieveAccessTokenException $exception) {
            return false;
        }

    }
}
