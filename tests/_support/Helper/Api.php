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

    public function importLegacyTool(
        $actor,
        $publishedVersion = '1.0.0',
        $draftVersionsCSV = '2.1.1,2.1.2',
        $draftOnlyToolDraftsCsv = '1.1.3'
    ) {
        $title = "Sterilising Services (with drafts)";
        $actor->am("Importing the Tool '{$title}' with a published version of '{$publishedVersion}'
            and the drafts '{$draftVersionsCSV}'");
        factory(\App\Models\Tool::class, 1)->create([
            'title' => $title,
            'import_id' => '1',
            'description' => 'Sterilising Services tool with drafts for testing',
            'source' => 'TESTING',
            'import_version' => '0.0.1'
        ]);

        // Import a single tool schema
        Artisan::call('tool:import', [
            '--env' => 'testing',
            'toolName' => 'STERILISING_SERVICES_(G)',
            'toolOverride' => \App\Models\Tool::where('title', '=', $title)->orderBy('created_at', 'desc')->first(),
            '--defaultVersion' => $publishedVersion,
            '--makeDrafts' => true,
            '--draftVersions' => $draftVersionsCSV,
            '--clearExistingTools' => true
        ]);



        $title = "PODIATRY CLINICAL PRACTICES";
        $actor->am("Importing the Tool '{$title}' with no published versions
            and the drafts '{$draftOnlyToolDraftsCsv}'");

        factory(\App\Models\Tool::class, 1)->create([
            'title' => $title,
            'import_id' => '1',
            'description' => 'A tool with no published versions',
            'source' => 'TESTING',
            'import_version' => '0.0.1'
        ]);

        Artisan::call('tool:import', [
            '--env' => 'testing',
            'toolName' => 'PODIATRY_CLINICAL_PRACTICES',
            'toolOverride' => \App\Models\Tool::where('title', '=', $title)->orderBy('created_at', 'desc')->first(),
            '--makeDrafts' => true,
            '--draftVersions' => $draftOnlyToolDraftsCsv,
            '--clearExistingTools' => false
        ]);
    }

    public function getTool($actor, $toolId, $version, $status = 'version', $expectedCode = 200)
    {
        $actor->sendGET("/tool/{$toolId}/{$status}/{$version}");
        $actor->seeResponseCodeIs($expectedCode);
    }

    public function getDraftTool($actor, $toolId, $version, $expectedCode = 200)
    {
        $actor->expectTo("see that the draft version '{$version}' gives a response code of '${expectedCode}'");
        $this->getTool($actor, $toolId, $version, 'draft', $expectedCode);
    }

    public function getPublishedTool($actor, $toolId, $version, $expectedCode = 200)
    {
        $actor->expectTo("see that the published version '{$version}' gives a response code of '${expectedCode}'");
        $this->getTool($actor, $toolId, $version, 'version', $expectedCode);
    }

    public function publishTool($actor, $toolId, $version, $expectedCode = 200)
    {
        $actor->expectTo("see that publishing version '{$version}' gives a response code of '${expectedCode}'");
        $actor->sendPut("/tool/{$toolId}/draft/{$version}/publish");
        $actor->seeResponseCodeIs($expectedCode);
    }

    public function reviewTool($actor, $toolId, $version, $action, $expectedCode = 200)
    {
        $actor->expectTo("see that reviewing version '{$version}' gives a response code of '${expectedCode}'");
        $actor->sendPut("/tool/{$toolId}/draft/{$version}/review/{$action}");
        $actor->seeResponseCodeIs($expectedCode);
    }

    public function getReviewTool($actor, $toolId, $version, $expectedCode = 200)
    {
        $actor->expectTo("see that the review version '{$version}' gives a response code of '${expectedCode}'");
        $this->getTool($actor, $toolId, $version, 'review', $expectedCode);
    }


    public function archiveDraftTool($actor, $toolId, $version, $expectedCode = 200)
    {
        $actor->expectTo("see that the archived version '{$version}' gives a response code of '${expectedCode}'");
        $actor->sendDelete("/tool/{$toolId}/draft/{$version}/archive");
        $actor->seeResponseCodeIs($expectedCode);
    }

    public function seeToolVersionIs($actor, $version)
    {
        $actor->expectTo("see that the tool version is '{$version}'");
        $parts = explode('.', $version);
        $actor->seeResponseContainsJson([
            'data' => [
                    'attributes' => [
                        'id' => $version,
                        'schema' => [
                            'options' => [
                                'version' => [
                                    'major' => $parts[0],
                                    'minor' => $parts[1],
                                    'patch' => $parts[2]
                                ]
                            ]
                        ]
                    ]
                ]
        ]);
    }
}
