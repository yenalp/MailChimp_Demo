<?php
namespace Page;

class ReasonModal extends Modal
{

    public $reasonForm;

    public function __construct(
        \Codeception\Actor $I,
        $elementId,
        $confirmBtnSelector = '#editor-save',
        $cancelBtnSelector = '#editor-cancel'
    ) {
        parent::__construct($I, $elementId, $confirmBtnSelector, $cancelBtnSelector);
        $this->reasonForm = new JsonForm($this->tester, "#{$this->elementId}", [], [], '#modal-editor');
    }

    public function waitUntilLoaded()
    {
        parent::waitUntilLoaded();
        $this->reasonForm->waitUntilLoaded();
    }

    public function enterAReason($text)
    {
        $this->reasonForm->fillField('item_log_reason', $text);
    }
}
