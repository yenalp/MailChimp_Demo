<?php
namespace Helper\Entities;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
// See here for details: http://codeception.com/docs/10-WebServices#REST

class ActivityLogHelper extends \Codeception\Module
{
    /**
     * @Given one or more Activity Logs exist
     */
    public function oneOrMoreActivityLogsExist(\Behat\Gherkin\Node\TableNode $entities)
    {
        // Clears all existing entries as ones created during login
        // break the test cases.
        \App\Models\ActivityLog::getQuery()->delete();
        
        foreach ($entities->getRows() as $index => $row) {
            if ($index === 0) { // first row to define fields
                $keys = $row;
                continue;
            }
            $entity = $this->createActivityLog(array_combine($keys, $row));
        }
    }

    public function createActivityLog($properties)
    {
        return factory(\App\Models\ActivityLog::class, 1)->create($properties);
    }
}
