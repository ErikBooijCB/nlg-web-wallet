Feature: access-token-lifecycle
  In order to be able to control access to Gulden wallet data
  As a user
  I need to be able to create, verify and revoke access tokens

  Scenario: Obtain an access token with valid credentials
    Given I try to log in as "john@doe.com" with password "testtest"
    Then the response should be JSON with a 201 status code
    And the status should be "ok"
    And it should contain an access token, with an expiration date and refresh token

  Scenario: Obtain an access token with invalid credentials
    Given I try to log in as "wrong@user.com" with password "wrong-pass"
    Then the response should be JSON with a 401 status code
    And it should be an error response

  Scenario Outline: Obtain an access token from an invalid request
    Given I send an incomplete request: "<request_body>"
    Then the response should be JSON with a 400 status code
    And it should be an error response

    Examples:
      | request_body                |
      | { "email": "john@doe.com" } |
      | { "password": "testtest" }  |
      | { }                         |

  Scenario: Retrieve details for a valid token
    Given I have obtained an access token
    When I fetch the token details
    Then the response should be JSON with a 200 status code
    And it should expose the token expiration

  Scenario: Retrieve details for an invalid token
    Given I do not have a valid token
    When I fetch the token details
    Then the response should be JSON with a 404 status code
