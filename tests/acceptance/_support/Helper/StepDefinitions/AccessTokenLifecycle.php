<?php
declare(strict_types=1);

namespace Helper\StepDefinitions;

use Codeception\Util\Fixtures;

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
     * @Given I have obtained an access token
     */
    public function iHaveObtainedAnAccessToken()
    {
        $this->haveHttpHeader('Content-type', 'application/json');
        $this->sendPOST('/access-tokens', ['email' => 'john@doe.com', 'password' => 'testtest']);

        Fixtures::add('accessToken', $this->grabDataFromResponseByJsonPath('data.accessToken')[0]);
    }

    /**
     * @Given I do not have a valid token
     */
    public function iDoNotHaveAValidToken()
    {
        Fixtures::add('accessToken', '0000000000000000000000000000000000000000000000000000000000000000');
    }

    /**
     * @When I fetch the token details
     */
    public function iFetchTheTokenDetails()
    {
        $this->sendGET('/access-tokens/' . Fixtures::get('accessToken'));
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

    /**
     * @Then it should expose the token expiration
     */
    public function itShouldExposeTheTokenExpiration()
    {
        $this->seeResponseMatchesJsonType([
            'data' => [
                'expires'      => 'string:date',
            ],
        ]);
    }
}
