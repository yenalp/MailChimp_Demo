<?php
namespace Helper\Api;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
// See here for details: http://codeception.com/docs/10-WebServices#REST

class ApiFacilityData extends \Codeception\Module
{
    public function facilityEntityData()
    {
        $entityData = [
            '1' => [
                'owner_id' => 1,
                'manager_owner_id' => 1,
                'name' => 'Epworth Cliveden',
                'timezone' => 'Australia/Melbourne',
                'state' => 'VIC',
                'country' => 'AU',
                'service_level' => 2,
                'category' => 6,
            ]
        ];

        return $entityData;
    }
}
