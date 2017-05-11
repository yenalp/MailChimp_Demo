<?php
namespace Page\Admin\User;

use Page\DataTable;
use Page\Button;
use Page\ReasonModal;

class ViewAdmin extends View
{

    public $editButton;
    public $disableButton;
    public $enableButton;
    public $masqueradeButton;
    public $deleteButton;
    public $resetButton;
    public $reasonEnableModal;
    public $reasonDisableModal;
    public $reasonDeleteModal;

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);
        $this->editButton = new Button($this->tester, '#edit-user-button');
        $this->disableButton = new Button($this->tester, '#disable-user-button');
        $this->enableButton = new Button($this->tester, '#enable-user-button');
        $this->masqueradeButton = new Button($this->tester, '#masquerade-user-button');
        $this->deleteButton = new Button($this->tester, '#delete-user-button');
        $this->resetButton = new Button($this->tester, '#reset-password-user-button');
        $this->reasonEnableModal = new ReasonModal($this->tester, 'user-enable-modal');
        $this->reasonDisableModal = new ReasonModal($this->tester, 'user-disable-modal');
        $this->reasonDeleteModal = new ReasonModal($this->tester, 'user-delete-modal');
    }

    public function waitUntilLoaded($timeout = 10)
    {
        parent::waitUntilLoaded($timeout);
        $this->editButton->waitUntilLoaded($timeout);
        $this->deleteButton->waitUntilLoaded($timeout);
        $this->resetButton->waitUntilLoaded($timeout);
    }
}
