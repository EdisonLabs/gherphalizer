Feature: Contact form

  Visitors to the website
  Should be able to contact the site owners through a form
  So that they can have their questions answered

  Scenario: Providing valid input
    Given that I visit the contact page
    When I fill in the name and message field and submit
    Then I should get a confirmation message

  Scenario: Providing invalid input
    Given that I visit the contact page
    When I fill in the message but no name and submit
    Then I should get an error message
