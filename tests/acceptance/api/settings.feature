Feature: settings
  In order to persist and retrieve settings
  As a user
  I want to use the API to manage these settings

  Scenario: Store a new setting
    Given I store a setting with key "setting-key" and value "setting-value"
    When I retrieve the setting "setting-key"
    Then the value should be "setting-value"

  Scenario: Update a setting
    Given I store a setting with key "setting-key" and value "setting-value"
    And I update the value for "setting-key" to "new-value"
    When I retrieve the setting "setting-key"
    Then the value should be "new-value"

  Scenario: Delete a setting
    Given I store a setting with key "setting-key" and value "setting-value"
    And I delete the setting "setting-key"
    Then the response should be JSON with a 202 status code
    When I retrieve the setting "setting-key"
    Then the response should be JSON with a 404 status code

  Scenario: Store a setting without passing a value
    Given I store a setting with key "setting-key" and omit the value
    Then the response should be JSON with a 400 status code
