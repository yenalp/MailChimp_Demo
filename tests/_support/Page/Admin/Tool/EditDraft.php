<?php
namespace Page\Admin\Tool;

use Page\Admin\Tool\BaseAdminToolPage;
use Page\Modal;
use Page\JsonForm;

class EditDraft extends BaseAdminToolPage
{
    public $publishModal;
    public $reasonModalForm;
    public $publishModalId = "tool-draft-publish-modal";

    public function getUrl($params)
    {
        return parent::getUrl($params) . "/{$params['toolId']}/edit/{$params['version']}/draft";
    }

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);

        $this->publishModal = new Modal($this->tester, "{$this->publishModalId}");
        $this->reasonModalForm = new JsonForm($this->tester, "#{$this->publishModalId} .modal-body");
    }

    public function publishDraft()
    {
        $this->waitUntilLoaded(15);
        $this->tester->waitForElement("#publish-draft-tool", 2);
        $this->tester->waitForElementVisible("#publish-draft-tool", 5);
        $this->tester->click('#publish-draft-tool');
    }

    public function enterReasonForPublish($reasonText)
    {
        $this->publishModal->waitUntilLoaded(20);

        $this->reasonModalForm->waitUntilLoaded(20);
        $this->reasonModalForm->fillField('root[item_log_reason]', $reasonText);
    }

    public function confirmPublication()
    {
        $this->publishModal->confirm();
        $this->tester->wait(2);
    }
}
