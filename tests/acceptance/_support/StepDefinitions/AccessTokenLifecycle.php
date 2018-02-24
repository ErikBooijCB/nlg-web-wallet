<?php
declare(strict_types=1);

trait AccessTokenLifecycle
{
    /**
     * @Given I try to log in as :arg1 with password :arg2
     */
    public function iTryToLogInAsWithPassword($email, $password)
    {
        $this->haveHttpHeader('Content-type', 'application/json');
        $this->sendPOST('/access-tokens', ['email' => $email, 'password' => $password]);
    }

    /**
     * @Given /I send an incomplete request: (.*)/
     */
    public function iSendAnIncompleteRequest($requestBody)
    {
        $requestBody = json_decode($requestBody, true);

        $this->haveHttpHeader('Content-type', 'application/json');
        $this->sendPOST('/access-tokens', $requestBody);
    }

    /**
     * @Then it should contain an access token, with an expiration date and refresh token
     */
    public function iShouldGetAnAccessTokenWithAnExpirationDateAndRefreshToken()
    {
        $this->seeResponseMatchesJsonType([
            'data' => [
                'accessToken'  => 'string',
                'expires'      => 'string:date',
                'refreshToken' => 'string',
            ],
        ]);
    }
}
