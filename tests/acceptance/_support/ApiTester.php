<?php

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;
    use Helper\StepDefinitions\AccessTokenLifecycle;
    use Helper\StepDefinitions\Settings;

    /**
     * @Then the response should be JSON with a :statusCode status code
     */
    public function theResponseShouldBeJson(int $statusCode)
    {
        $this->seeResponseIsJSON();
        $this->seeResponseCodeIs($statusCode);
    }

    /**
     * @Then the status should be :status
     */
    public function theStatusShouldBe(string $status)
    {
        $this->seeResponseContainsJson(['status' => $status]);
    }

    /**
     * @Then it should be an error response
     */
    public function itShouldBeAnErrorResponse()
    {
        $this->seeResponseContainsJson(['status' => 'error']);
        $this->seeResponseMatchesJsonType([
            'message' => 'string',
        ]);
    }
}
