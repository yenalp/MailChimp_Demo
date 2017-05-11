<?php
namespace Helper\Entities;

use Illuminate\Support\Facades\Hash;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
// See here for details: http://codeception.com/docs/10-WebServices#REST

class LoginHelper extends \Codeception\Module
{
    public function createLogin($properties)
    {
        if (isset($properties['user_name'])) {
            if (\App\Models\User::where('user_name', $properties['user_name'])->exists()) {
                return;
            }
        }

        return factory(\App\Models\User::class, 1)->create($properties);
    }

    /**
     * @Given one or more Login exist
     */
    public function oneOrMoreLoginExist(\Behat\Gherkin\Node\TableNode $entities)
    {
        foreach ($entities->getRows() as $index => $row) {
            if ($index === 0) { // first row to define fields
                $keys = $row;
                continue;
            }
            $data = array_combine($keys, $row);
            $data['password'] = Hash::make($data['password']);
            $entity = $this->createLogin($data);
        }
    }

    public function iAmManager()
    {
        $properties = [
           'first_name' => 'Test',
           'last_name' => 'Manager',
           'user_name' => 'manager',
           'email' => 'manager@test.com',
           'password' => Hash::make('password'),
           'user_type' => 'MANAGER',
           'disabled' => false
        ];

        $this->createLogin($properties);
    }


    public function iAmAdministrator()
    {
        $properties = [
          'first_name' => 'Test',
          'last_name' => 'Admin',
          'user_name' => 'admin',
          'email' => 'admin@test.com',
          'password' => Hash::make('password'),
          'user_type' => 'ADMIN',
          'disabled' => false
        ];

        $this->createLogin($properties);
    }
}
