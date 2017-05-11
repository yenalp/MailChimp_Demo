<?php
namespace Helper\Entities;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
// See here for details: http://codeception.com/docs/10-WebServices#REST

class OwnerDefinedCategoryHelper extends \Codeception\Module
{
    public function createOwnerDefinedCategory($properties)
    {
        return factory(\App\Models\OwnerDefinedCategory::class, 1)->create($properties);
    }

    /**
     * @Given one or more Facilities exist
     */
    public function oneOrMoreOwnerDefinedCategoryExist(\Behat\Gherkin\Node\TableNode $entities)
    {
        foreach ($entities->getRows() as $index => $row) {
            if ($index === 0) { // first row to define fields
                $keys = $row;
                continue;
            }
            $entity = $this->createOwnerDefinedCategory(array_combine($keys, $row));
        }
    }
}
