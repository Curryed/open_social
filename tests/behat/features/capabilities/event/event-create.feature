@api @event @stability @javascript @DS-406 @stability-1 @event-create
Feature: Create Event
  Benefit: In order to connect with other people offline
  Role: As a Verified
  Goal/desire: I want to create Events

  @verified @perfect @critical
  Scenario: Successfully create event
    Given I am logged in as an "verified"
    And I am on "user"
    And I click "Events"
    And I click "Create Event"
    When I fill in the following:
      | Title                                  | This is a test event |
      | edit-field-event-date-0-value-date     | 2025-01-01           |
      | edit-field-event-date-end-0-value-date | 2025-01-01           |
      | edit-field-event-date-0-value-time     | 11:00:00             |
      | Location name                          | Technopark           |
    And I fill in the "edit-body-0-value" WYSIWYG editor with "Body description text."
    And I select "UA" from "Country"
    And I wait for AJAX to finish
    Then I should see "City"
    And I fill in the following:
      | City           | Lviv           |
      | Street address | Fedkovycha 60a |
      | Postal code    | 79000          |
      | Oblast         | Lviv oblast    |
    And I press "Create event"
    Then I should see "This is a test event has been created."
    And I should see "THIS IS A TEST EVENT"
    And I should see "Technopark" in the "Main content"
    And I should see "Body description text" in the "Main content"
    And I should see "Fedkovycha 60a" in the "Main content"
    And I should see "79000" in the "Main content"
    And I should see "Lviv" in the "Main content"
    And I should see "Lviv oblast" in the "Main content"
    And I should see "1 Jan '25 11:00" in the "Main content"

    # Quick edit
    Given I click "Edit content"
    When I fill in the following:
      | Title | This is a test event - edit |
    And I show hidden checkboxes
    And I check the box "edit-field-event-all-day-value"
    And I press "Save"
    Then I should see "Event This is a test event - edit has been updated"
    And I should see "THIS IS A TEST EVENT - EDIT"
    And I should see "1 Jan '25"
    And I should not see "1 Jan '25 11:00"

    # LU should not be able to create events.
    Given I disable that the registered users to be verified immediately
    When I am logged in as an "authenticated user"
      And I am on "user"
    Then I should not see the link "Events"
    When I am on "node/add/event"
    Then I should see "Access denied"
      And I should see "You are not authorized to access this page."
      And I enable that the registered users to be verified immediately
