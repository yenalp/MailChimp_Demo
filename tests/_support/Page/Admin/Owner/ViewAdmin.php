<?php
namespace Page\Admin\Owner;

use Page\DataTable;
use Page\Button;
use Page\ReasonModal;

class ViewAdmin extends View
{
    public $archiveButton;
    public $reasonModal;
    public $categoryCreate;

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);
        ;
        $this->editButton = new Button($this->tester, '#owner-edit-btn');
        $this->archiveButton = new Button($this->tester, '#owner-delete-btn');
        $this->reasonModal = new ReasonModal($this->tester, 'owner-delete-modal');
        $this->categoryCreate = new Button($this->tester, '#owner-create-category');
    }

    public function waitUntilLoaded($timeout = 10)
    {
        parent::waitUntilLoaded($timeout);
        $this->editButton->waitUntilLoaded($timeout);
        $this->archiveButton->waitUntilLoaded($timeout);
        $this->categoryCreate->waitUntilLoaded($timeout);
    }

    public function modalReasonIsVisible()
    {
        $this->reasonModal->waitUntilLoaded();
    }
}
