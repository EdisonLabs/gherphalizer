Feature: Serialize Gherkin to PHP

  The class
  Should be able to serialize a Gherkin feature to a PHP file
  So that feature can be reflected on with PHP

  Scenario: Feature input
    Given that a feature node is provided
    Then a string should be returned