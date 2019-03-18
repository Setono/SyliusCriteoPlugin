@tracking
Feature: Tracking is in place
  In order to be tracked
  As a Visitor
  I need to be able to find the tracking library in the code

  Background:
    Given the store operates on a single channel in "United States"
    And a criteo account with account id "1234" is made for current channel

  Scenario: Visiting the home page
    When a customer visits the home page
    And there is an enabled criteo account for current channel
    Then he will find the tracking library with account id "1234" in the code
