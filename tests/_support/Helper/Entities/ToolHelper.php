<?php
namespace Helper\Entities;

use \App\Models\Tool;
use Illuminate\Support\Facades\Artisan;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
// See here for details: http://codeception.com/docs/10-WebServices#REST

class ToolHelper extends \Codeception\Module
{
    public function createTool($properties)
    {
        return factory(\App\Models\Tool::class, 1)->create($properties);
    }

    public function deleteTool($model)
    {
        $tool = Tool::findOrFail($model->id);
        $tool->delete();
    }

    /**
     * @Given one or more Tool exist
     */
    public function oneOrMoreToolExist(\Behat\Gherkin\Node\TableNode $entities)
    {
        foreach ($entities->getRows() as $index => $row) {
            if ($index === 0) { // first row to define fields
                $keys = $row;
                continue;
            }

            $dataDelete = false;
            $data = array_combine($keys, $row);
            if ($data['deleted'] === 'true') {
                $dataDelete = true;
            }
            unset($data['deleted']);
            $entity = $this->createTool($data);
            if ($dataDelete === true) {
                $this->deleteTool($entity);
            }
        }
    }

    public function seedToolWithDrafts($draftVersionsCSV)
    {
        // Create a 'Sterilising Services tool' with drafts
        $title = "Sterilising Services (with drafts)";
        factory(\App\Models\Tool::class, 1)->create([
            'title' => $title,
            'import_id' => '1',
            'description' => 'Sterilising Services tool with drafts for testing',
            'source' => 'HICMR',
            'import_version' => '0.0.1'
        ]);

        // Import a single tool schema
        Artisan::call('tool:import', [
            '--env' => 'testing',
            'toolName' => 'STERILISING_SERVICES_(G)',
            'toolOverride' => \App\Models\Tool::where('title', '=', $title)->orderBy('created_at', 'desc')->first(),
            '--publishedVersion' => '2.0.0',
            '--makeDrafts' => true,
            '--draftVersions' => $draftVersionsCSV,
            '--clearExistingTools' => true
        ]);
    }

    /**
     * @Given I choose to publish a draft of a Tool
     */
    public function chooseToPublishADraftOfATool()
    {
        $pages = $this->getModule('\Helper\Admin\AdminPages')->pages;
        $pages['tool']['edit-draft']->publishDraft();
    }

    /**
     * @When I enter a reason for publishing the draft
     */
    public function enterAReasonForPublishingTheDraft()
    {
        $pages = $this->getModule('\Helper\Admin\AdminPages')->pages;
        $pages['tool']['edit-draft']->enterReasonForPublish('This draft is being published via an automated test');
    }

    /**
     * @When I confirm publication
     */
    public function confirmPublication()
    {
        $pages = $this->getModule('\Helper\Admin\AdminPages')->pages;
        $pages['tool']['edit-draft']->confirmPublication();
        $pages['tool']['edit-draft']->publishModal->waitUntilHidden();
    }

    /**
     * @Then the draft of a Tool status changes to :arg1
     */
    public function checkTheToolStatusIs($status)
    {
        $pages = $this->getModule('\Helper\Admin\AdminPages')->pages;

        $pages['tool']['edit']->waitUntilLoaded(30);
        $pages['tool']['edit']->amOnThePage(['toolId' => '1']);
        $pages['tool']['edit']->currentPublishedVersionIs('2.1.1');
        $pages['tool']['edit']->currentPublishedStatusIs($status);
    }

    /**
     * @Then the version is no longer editable
     */
    public function checkTheVersionIsNoLongerEditable()
    {
        $pages = $this->getModule('\Helper\Admin\AdminPages')->pages;
        $this->assertFalse($pages['tool']['edit']->canSelectVersion('2.1.1'));
    }

    /**
     * @Then the Activity Log description should contain the draft Version Number
     */
    public function theActivityLogDescriptionShouldContainTheDraftVersionNumber()
    {
        //
    }

    /**
    * @Given I have selected an unpublished draft of a Tool
    */
    public function haveSelectedAnUnpublishedDraftOfATool()
    {
        $this->seedToolWithDrafts('2.1.1');
        $pages = $this->getModule('\Helper\Admin\AdminPages')->pages;
        $pages['dashboard']['view']->sidebarNavLink('Tools');
        $pages['tool']['list']->amOnThePage([]);
        $pages['tool']['list']->waitUntilLoaded();
        $pages['tool']['list']->dataTable->selectFirstItem();

        $pages['tool']['edit']->waitUntilLoaded();
        $pages['tool']['edit']->amOnThePage(['toolId' => '1']);
        $this->assertTrue($pages['tool']['edit']->canSelectVersion('2.1.1'));
        $pages['tool']['edit']->selectFirstDraft();
        $pages['tool']['edit-draft']->waitUntilLoaded();
        $pages['tool']['edit-draft']->amOnThePage(['toolId' => '1', 'version' => '2.1.1']);
    }
}
