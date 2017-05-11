<?php
namespace Helper;

use Illuminate\Support\Facades\Artisan;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
// See here for details: http://codeception.com/docs/10-WebServices#REST

class Api extends \Codeception\Module
{
    public function loadSeeder($seederName)
    {
        Artisan::call('db:seed', ['--env' => 'testing', '--class' => $seederName]);
    }

    public function refreshTheDatabase()
    {
        Artisan::call('migrate:refresh', ['--env' => 'testing']);
    }

    public function clearTools()
    {
        Artisan::call('tool:clear', ['--env' => 'testing']);
    }

    public function runEntityDeleteTests($actor, $lowerCaseName, $camelCaseName)
    {
        $actor->wantTo("Test that correct response is returned when deleting {$camelCaseName} entities via the API.");

        $actor->getReadyToRunTests("/{$lowerCaseName}", "{$camelCaseName}TestSeeder");

        $actor->amGoingTo("Get one of {$camelCaseName} entities from the API to make sure it exists");
        $actor->get(1);
        $actor->seeResponseCodes(200, 'OK');
        $actor->seeResponseContainsJson($actor->getEntityData($lowerCaseName, '1'));

        $actor->amGoingTo("Delete the {$camelCaseName} via the API");
        $actor->delete(1);
        $actor->seeResponseCodes(200, 'OK');
        $actor->seeResponseContainsJson($actor->getEntityData($lowerCaseName, '1'));

        $actor->amGoingTo("Check that it is no longer returned when requested");
        $actor->get(1, 404);
        $actor->checkFor404();

        $actor->amGoingTo("Make sure the correct response is returned when I request an entity that does not exist");
        $actor->delete('99999', 404);
        $actor->checkFor404();
    }

    public function runCannotDeleteTests($actor, $lowerCaseName, $camelCaseName)
    {
        $actor->wantTo("Test that non-admin users cannot delete {$camelCaseName} entities via the API.");

        $actor->getReadyToRunTests("/{$lowerCaseName}", "{$camelCaseName}TestSeeder");

        $actor->amGoingTo("Get one of {$camelCaseName} entities from the API to make sure it exists");
        $actor->get(1);
        $actor->seeResponseCodes(200, 'OK');
        $actor->seeResponseContainsJson($actor->getEntityData($lowerCaseName, '1'));

        $actor->amGoingTo("Try and delete the {$camelCaseName} via the API");
        $actor->delete(1, 401);

        $actor->amGoingTo("Make sure the {$camelCaseName} entity was not deleted");
        $actor->get(1);
        $actor->seeResponseCodes(200, 'OK');
        $actor->seeResponseContainsJson($actor->getEntityData($lowerCaseName, '1'));
    }

    public function runEntityGetTests($actor, $lowerCaseName, $camelCaseName)
    {
        $actor->getReadyToRunTests("/{$lowerCaseName}", "{$camelCaseName}TestSeeder");

        $actor->wantTo('Test that correct response is returned when selecting {$camelCaseName} entities via the API.');
        $actor->get(1);
        $actor->seeResponseContainsJson($actor->getEntityData($lowerCaseName, '1'));

        $actor->get(99999, 404);
        $actor->checkFor404();
    }

}
