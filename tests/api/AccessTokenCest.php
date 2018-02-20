<?php
declare(strict_types=1);

use Codeception\Example;
use Codeception\Util\HttpCode;

class AccessTokenCest
{
    /**
     * @param ApiTester $I
     */
    public function _before(ApiTester $I)
    {
    }

    /**
     * @param ApiTester $I
     */
    public function _after(ApiTester $I)
    {
    }

    /**
     * @param ApiTester $I
     */
    public function requestAnAccessTokenWithValidCredentials(ApiTester $I)
    {
        $I->haveHttpHeader('Content-type', 'application/json');
        $I->sendPOST('/access-tokens', ['email' => 'john@doe.com', 'password' => 'testtest']);
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJSON();
        $I->seeResponseContainsJson(['status' => 'ok']);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'accessToken'  => 'string',
                'expires'      => 'string:date',
                'refreshToken' => 'string',
            ],
        ]);
    }

    /**
     * @param ApiTester $I
     */
    public function requestAnAccessTokenWithInvalidCredentials(ApiTester $I)
    {
        $I->haveHttpHeader('Content-type', 'application/json');
        $I->sendPOST('/access-tokens', ['email' => 'wrong@user.com', 'password' => 'wrongwrong']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJSON();
        $I->seeResponseContainsJson(['status' => 'error']);
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
        ]);
    }

    /**
     * @param ApiTester $I
     *
     * @example { "request": { "email": "john@doe.com" } }
     * @example { "request": { "password": "testtest" } }
     * @example { "request": { } }
     */
    public function requestAnAccessTokenWithAnIncompleteRequestBody(ApiTester $I, Example $example)
    {
        $I->haveHttpHeader('Content-type', 'application/json');
        $I->sendPOST('/access-tokens', $example['request']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJSON();
        $I->seeResponseContainsJson(['status' => 'error']);
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
        ]);
    }
}
