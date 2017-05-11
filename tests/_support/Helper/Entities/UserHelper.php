<?php
namespace Helper\Entities;

use Illuminate\Support\Facades\Hash;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
// See here for details: http://codeception.com/docs/10-WebServices#REST

class UserHelper extends \Codeception\Module
{
    public function createUser($properties)
    {
        return factory(\App\Models\User::class, 1)->create($properties);
    }

    /**
     * @Given one or more Users exist
     */
    public function oneOrMoreUserExist(\Behat\Gherkin\Node\TableNode $entities)
    {
        foreach ($entities->getRows() as $index => $row) {
            if ($index === 0) { // first row to define fields
                $keys = $row;
                continue;
            }
            $data = array_combine($keys, $row);
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            $entity = $this->createUser($data);
        }
    }
}
