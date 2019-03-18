@tracking
Feature: Tracking is not in place
  In order to not be tracked
  As a Visitor
  I need to not be able to find the tracking library in the code

  Background:
    Given the store operates on a single channel in "United States"

  Scenario: Visiting the home page
    When a customer visits the home page
    And there is no criteo account for current channel
    Then he will not find the tracking library in the code

  Scenario: Visiting the home page
    When a customer visits the home page
    And there is an disabled criteo account for current channel
    Then he will not find the tracking library in the code
