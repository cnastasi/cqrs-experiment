Feature: Counter should count

  Scenario: Counter should increase by 1
    Given the counter set to 0
    When plus button is pressed
    Then the counter value is 1

  Scenario: Counter should increase by 2
    Given the counter set to 0
    When plus button is pressed
    And plus button is pressed
    Then the counter value is 2

  Scenario: Counter should increase by 3 starting from 2
    Given the counter set to 2
    When plus button is pressed
    And plus button is pressed
    And plus button is pressed
    Then the counter value is 5

  Scenario: Counter should decrease by 1 starting from 1
    Given the counter set to 1
    When minus button is pressed
    Then the counter value is 0


  Scenario: Counter should not decrease by 2 starting from 1
    Given the counter set to 1
    When minus button is pressed
    When minus button is pressed
    Then the counter value is 0

  Scenario: Player will win a puppy
    Given the counter set to 1
    When minus button is pressed
    When minus button is pressed
    When minus button is pressed
    When minus button is pressed
    When minus button is pressed
    When minus button is pressed
    Then the counter value is 0
    And he win a puppy