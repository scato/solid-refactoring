<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\Process\Process;
use PHPUnit_Framework_Assert as PHPUnit;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    private $pdo;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->pdo = new PDO('mysql:dbname=solid_refactoring;host=127.0.0.1', 'solid', 'R3f@ct0r!ng');
    }

    /**
      * @BeforeScenario
      */
     public function cleanDB(BeforeScenarioScope $scope)
     {
         $stat = $this->pdo->prepare('TRUNCATE groups');
         $stat->execute();

         $stat = $this->pdo->prepare('TRUNCATE users');
         $stat->execute();
     }

    /**
      * @AfterScenario
      */
     public function cleanCSV(AfterScenarioScope $scope)
     {
         unlink('users.csv');
     }

    /**
     * @Given I have a CSV file with the following:
     */
    public function iHaveACsvFileWithTheFollowing(PyStringNode $string)
    {
        file_put_contents('users.csv', $string);
    }

    /**
     * @Given I have the following groups in the database:
     */
    public function iHaveTheFollowingGroupsInTheDatabase(TableNode $table)
    {
        $stat = $this->pdo->prepare('INSERT INTO groups VALUES (:id, :name)');

        foreach ($table as $group) {
            $stat->execute($group);
        }
    }

    /**
     * @Given I have the following users in the database:
     */
    public function iHaveTheFollowingUsersInTheDatabase(TableNode $table)
    {
        $stat = $this->pdo->prepare('INSERT INTO users VALUES (:id, :username, :password, :group_id)');

        foreach ($table as $user) {
            $stat->execute($user);
        }
    }

    /**
     * @When I run the import script
     */
    public function iRunTheImportScript()
    {
        $process = new Process('php import.php');
        $process->run();

        if (trim($process->getErrorOutput()) !== '') {
            throw new RuntimeException($process->getErrorOutput());
        }

        if (trim($process->getOutput()) !== '') {
            throw new RuntimeException($process->getOutput());
        }
    }

    /**
     * @Then I should see these groups in the database:
     */
    public function iShouldSeeTheseGroupsInTheDatabase(TableNode $table)
    {
        $stat = $this->pdo->prepare('SELECT * FROM groups ORDER BY id');
        $stat->execute();

        $expected = $table->getHash();
        $actual = $stat->fetchAll(PDO::FETCH_ASSOC);

        PHPUnit::assertEquals($expected, $actual);
    }

    /**
     * @Then I should see these users in the database:
     */
    public function iShouldSeeTheseUsersInTheDatabase(TableNode $table)
    {
        $stat = $this->pdo->prepare('SELECT * FROM users ORDER BY id');
        $stat->execute();

        $expected = $table->getHash();
        $actual = $stat->fetchAll(PDO::FETCH_ASSOC);

        PHPUnit::assertEquals($expected, $actual);
    }
}
