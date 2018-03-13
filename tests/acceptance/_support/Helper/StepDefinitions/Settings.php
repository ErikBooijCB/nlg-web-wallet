<?php
declare(strict_types=1);

namespace Helper\StepDefinitions;

trait Settings
{
    /**
     * @Given I store a setting with key ":settingKey" and value ":settingValue"
     * @param string $settingKey
     * @param string $settingValue
     */
    public function givenIStoreASettingWithKeyAndValue(string $settingKey, string $settingValue)
    {
        $this->haveHttpHeader('Content-type', 'application/json');
        $this->sendPUT("/settings/{$settingKey}", [
            'value' => $settingValue
        ]);
    }

    /**
     * @Given I store a setting with key ":settingKey" and omit the value
     * @param string $settingKey
     */
    public function givenIStoreASettingAndOmitTheValue(string $settingKey)
    {
        $this->haveHttpHeader('Content-type', 'application/json');
        $this->sendPUT("/settings/{$settingKey}", []);
    }

    /**
     * @Given I update the value for ":settingKey" to ":settingValue"
     * @param string $settingKey
     * @param string $settingValue
     */
    public function givenIUpdateTheValueForTo(string $settingKey, string $settingValue)
    {
        $this->haveHttpHeader('Content-type', 'application/json');
        $this->sendPUT("/settings/{$settingKey}", [
            'value' => $settingValue
        ]);
    }

    /**
     * @Given I delete the setting ":settingKey"
     * @param string $settingKey
     */
    public function givenIDeleteTheSetting(string $settingKey)
    {
        $this->sendDELETE("/settings/{$settingKey}");
    }

    /**
     * @When I retrieve the setting ":settingKey"
     * @param string $settingKey
     */
    public function whenIRetrieveTheSetting(string $settingKey)
    {
        $this->sendGET("/settings/{$settingKey}");
    }

    /**
     * @Then the value should be ":settingValue"
     * @param string $settingValue
     */
    public function thenTheValueShouldBe(string $settingValue)
    {
        $this->canSeeResponseContainsJson([
            'status' => 'ok',
            'data' => [
                'value' => $settingValue
            ]
        ]);
    }
}
