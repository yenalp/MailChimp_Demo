<?php
namespace Page\Admin\Tool;

use Page\Admin\Tool\BaseAdminToolPage;
use Page\HtmlTable;

class Edit extends BaseAdminToolPage
{
    public $draftsTable;

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);
        $this->draftsTable = new HtmlTable($I, '#table-tool-draft-version');
    }

    public function getUrl($params)
    {
        return parent::getUrl($params) . "/{$params['toolId']}";
    }

    public function waitUntilLoaded($timeout = 10)
    {
        parent::waitUntilLoaded($timeout);
        $this->draftsTable->waitUntilLoaded($timeout);
        // $this->tester->waitForElement('#table-tool-draft-version', $timeout);
    }

    public function selectFirstDraft()
    {
        $this->tester->click("#table-tool-draft-version tbody tr:first-child");
    }

    public function currentPublishedVersionIs($version)
    {
        $this->tester->see("Version: {$version}");
    }

    public function currentPublishedStatusIs($status)
    {
        $this->tester->see("Status: {$status}");
    }

    public function canSelectVersion($version)
    {
        $this->tester->comment("I check to see if I can edit tool version '{$version}'");
        return $this->draftsTable->columnContains('Version', $version);
    }
}
