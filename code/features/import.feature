Feature: import users from CSV file
    In order to have users in my database
    As an administrator
    I need to be able to run a script to get them there

    Scenario: first import
        Given I have a CSV file with the following:
        """
        username;password;group
        scato;ginga;admin
        corien;satnam;users
        """
        When I run the import script
        Then I should see these groups in the database:
        | id | name  |
        |  1 | admin |
        |  2 | users |
        And I should see these users in the database:
        | id | username | password                         | group_id |
        |  1 | scato    | 886a40482f08a4ba6e06f9d5f184a5f2 |        1 |
        |  2 | corien   | 5d46e52886d46015ab3bcc1a3e8f659f |        2 |


    Scenario: later import
        Given I have the following groups in the database:
        | id | name  |
        |  1 | admin |
        |  2 | users |
        And I have the following users in the database:
        | id | username | password                         | group_id |
        |  1 | scato    | 886a40482f08a4ba6e06f9d5f184a5f2 |        1 |
        |  2 | corien   | 5d46e52886d46015ab3bcc1a3e8f659f |        2 |
        And I have a CSV file with the following:
        """
        username;password;group
        scato;ginga;admin
        corien;satnam;admin
        zosia;miffy;users
        """
        When I run the import script
        Then I should see these groups in the database:
        | id | name  |
        |  1 | admin |
        |  2 | users |
        And I should see these users in the database:
        | id | username | password                         | group_id |
        |  1 | scato    | 886a40482f08a4ba6e06f9d5f184a5f2 |        1 |
        |  2 | corien   | 5d46e52886d46015ab3bcc1a3e8f659f |        1 |
        |  3 | zosia    | b3ea0520a1d6666f7a65c8e4538dfc44 |        2 |

