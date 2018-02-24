<?php
declare(strict_types=1);

namespace Helper\StepDefinitions;

use Codeception\Util\Fixtures;

trait AccessTokenLifecycle
{
    /**
     * @Given I do not have a valid token
     */
    public function givenIDoNotHaveAValidToken()
    {
        Fixtures::add('accessToken', '0000000000000000000000000000000000000000000000000000000000000000');
    }

    /**
     * @Given I have obtained an access token
     */
    public function givenIHaveObtainedAnAccessToken()
    {
        $this->haveHttpHeader('Content-type', 'application/json');
        $this->sendPOST('/access-tokens', ['email' => 'john@doe.com', 'password' => 'testtest']);

        Fixtures::add('accessToken', $this->grabDataFromResponseByJsonPath('data.accessToken')[0]);
    }

    /**
     * @Given /I send an incomplete request: (.*)/
     */
    public function givenISendAnIncompleteRequest($requestBody)
    {
        $requestBody = json_decode($requestBody, true);

        $this->haveHttpHeader('Content-type', 'application/json');
        $this->sendPOST('/access-tokens', $requestBody);
    }

    /**
     * @Given I try to log in as :arg1 with password :arg2
     */
    public function givenITryToLogInAsWithPassword($email, $password)
    {
        $this->haveHttpHeader('Content-type', 'application/json');
        $this->sendPOST('/access-tokens', ['email' => $email, 'password' => $password]);
    }

    /**
     * @Then it should contain an access token, with an expiration date and refresh token
     */
    public function thenIShouldGetAnAccessTokenWithAnExpirationDateAndRefreshToken()
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
    public function thenItShouldExposeTheTokenExpiration()
    {
        $this->seeResponseMatchesJsonType([
            'data' => [
                'expires'      => 'string:date',
            ],
        ]);
    }

    /**
     * @When I fetch the token details
     */
    public function whenIFetchTheTokenDetails()
    {
        $this->sendGET('/access-tokens/' . Fixtures::get('accessToken'));
    }

    /**
     * @When I revoke the token
     */
    public function whenIRevokeTheToken()
    {
        $this->sendDELETE('/access-tokens/' . Fixtures::get('accessToken'));
    }
}
