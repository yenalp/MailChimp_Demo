<?php
namespace Page\Admin\User;

use Page\JsonForm;
use Page\SpinnerButton;
use Page\Modal;

class Edit extends BaseAdminUserPage
{
    use \Page\Traits\IsEditPage;
    use \Page\Traits\HasForms;

    public $fieldTypes = [];

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);
        $this->saveButton = new SpinnerButton($I, "#editor-save");
        $this->fieldTypes = [
            'first_name' => JsonForm::FIELD_TYPES_TEXT,
            'last_name' => JsonForm::FIELD_TYPES_TEXT,
            'user_name' => JsonForm::FIELD_TYPES_TEXT,
            'email' => JsonForm::FIELD_TYPES_TEXT,
            'contact_number' => JsonForm::FIELD_TYPES_TEXT,
            'user_type' => JsonForm::FIELD_TYPES_SELECT,
        ];
        $this->fieldMappings = [
            'first name' => [
                'field_name' => 'first_name',
                'ignore'=> false,
                'error' => 'Value required'
            ],
            'last name' => [
                'field_name' => 'last_name',
                'ignore'=> false,
                'error' => 'Value required'
            ],
            'username' => [
                'field_name' => 'user_name',
                'ignore'=> false,
                'error' => 'Value required'
            ],
            'password' => [
                'field_name' => 'password',
                'ignore'=> true,
                'error' => 'Value must be at least 8 characters long'
            ],
            'confirm password' => [
                'field_name' => 'password_confirmation',
                'ignore'=> true,
                'error' => 'Value must be at least 8 characters long'
            ],
            'email' => [
                'field_name' => 'email',
                'ignore'=> false,
                'error' => 'Value required'
            ],
            'contact number' => [
                'field_name' => 'contact_number',
                'ignore'=> false,
                'error' => 'Value required'
            ],
            'user type' => [
                'field_name' => 'user_type',
                'ignore'=> false,
                'error' => 'Value required'
            ]
        ];

        $this->setEditForm(new JsonForm($I, '#edit-details', $this->fieldTypes));
    }

    public function waitUntilLoaded($timeout = 10)
    {
        parent::waitUntilLoaded($timeout);
        $this->editForm->waitUntilLoaded($timeout);
        $this->saveButton->waitUntilLoaded($timeout);
    }

    public function getUrl($params)
    {
        return parent::getUrl($params) . "/{$params['userId']}";
    }

    public function saveForm()
    {
        $this->saveButton->click();
    }
}
