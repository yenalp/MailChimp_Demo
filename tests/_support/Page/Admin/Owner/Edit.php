<?php
namespace Page\Admin\Owner;

use Page\JsonForm;
use Page\SpinnerButton;
use Page\Modal;

class Edit extends BaseAdminOwnerPage
{
    use \Page\Traits\IsEditPage;
    use \Page\Traits\HasForms;

    public $fieldTypes = [];

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);
        $this->saveButton = new SpinnerButton($I, "#editor-save");
        $this->fieldTypes = [
            'name' => JsonForm::FIELD_TYPES_TEXT,
            'acronym' => JsonForm::FIELD_TYPES_TEXT,
            'owner_type' => JsonForm::FIELD_TYPES_SELECT,
            'role_type' =>  JsonForm::FIELD_TYPES_SELECT,
        ];

        $this->setEditForm(new JsonForm($I, '#owner-update', $this->fieldTypes));
    }

    public function waitUntilLoaded($timeout = 10)
    {
        parent::waitUntilLoaded($timeout);
        $this->editForm->waitUntilLoaded($timeout);
        $this->saveButton->waitUntilLoaded($timeout);
    }

    public function getUrl($params)
    {
        return parent::getUrl($params) . "/{$params['ownerId']}";
    }

    public function saveForm()
    {
        $this->saveButton->click();
    }
}
