<?php
namespace Page;

class Modal
{
    public $elementId;
    protected $tester;
    protected $confirmBtnSelector;
    protected $cancelBtnSelector;

    public function __construct(
        \Codeception\Actor $I,
        $elementId,
        $confirmBtnSelector = '#editor-save',
        $cancelBtnSelector = '#editor-cancel'
    ) {
        $this->tester = $I;
        $this->elementId = $elementId;
        $this->confirmBtnSelector = $confirmBtnSelector;
        $this->cancelBtnSelector = $cancelBtnSelector;
    }

    public function waitUntilLoaded()
    {
        $this->tester->waitForElement("#{$this->elementId}", 10);
        $this->tester->waitForElementVisible("#{$this->elementId}", 10);
        $this->tester->waitForElementVisible("#{$this->elementId} .modal-body", 10);
        $this->tester->assertTrue(true, 'Modal is visible');
    }

    public function waitUntilHidden()
    {
        $this->tester->waitForElementNotVisible("#{$this->elementId} .modal-body", 20);
    }

    public function cancel()
    {
        $this->tester->waitForElement("#{$this->elementId} {$this->cancelBtnSelector}", 1);
        $this->tester->click("#{$this->elementId} {$this->cancelBtnSelector}");
    }

    public function confirm()
    {
        $this->tester->waitForElement("#{$this->elementId} {$this->confirmBtnSelector}", 1);
        $this->tester->click("#{$this->elementId} {$this->confirmBtnSelector}");
    }

    public function seeInModal($text)
    {
        $this->tester->see($text, "#{$this->elementId}");
    }

    public function getBodyText()
    {
        return $this->tester->grabTextFrom("#{$this->elementId} .modal-body .error-msg");
    }
}
