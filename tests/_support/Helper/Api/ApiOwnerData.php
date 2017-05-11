<?php
namespace Helper\Api;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
// See here for details: http://codeception.com/docs/10-WebServices#REST

class ApiOwnerData extends \Codeception\Module
{
    public function ownerEntityData()
    {
        $ownerEntityData = [
            '1' => [
                'name' => 'The Australian Health Care Reform Alliance',
                'acronym' => 'AHCRA',
                'role_type' => 'BOTH',
                'owner_type' => 'GOVERNMENT'
            ]
        ];

        return $ownerEntityData;
    }
}
