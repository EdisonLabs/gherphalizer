Feature: Comment form

  Visitors to the website
  Should be able to comment on content through a form
  So that they can have their opinions heard

  Scenario: Providing valid input
    Given that I visit an article page
    When I fill in the name and message field and submit
    Then I should get a confirmation message

  Scenario: Providing invalid input
    Given that I visit an article page
    When I fill in the name but no message and submit
    Then I should get an error message
