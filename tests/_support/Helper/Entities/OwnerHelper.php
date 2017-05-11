<?php
namespace Helper\Entities;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
// See here for details: http://codeception.com/docs/10-WebServices#REST

class OwnerHelper extends \Codeception\Module
{
    public function createOwner($properties)
    {
        return factory(\App\Models\Owner::class, 1)->create($properties);
    }

    /**
     * @Given one or more Owners exist
     */
    public function oneOrMoreOwnersExist(\Behat\Gherkin\Node\TableNode $entities)
    {
        foreach ($entities->getRows() as $index => $row) {
            if ($index === 0) { // first row to define fields
                $keys = $row;
                continue;
            }
            $entity = $this->createOwner(array_combine($keys, $row));
        }
    }
}
