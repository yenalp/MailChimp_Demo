<?php
namespace Page\Admin\OwnerDefinedCategory;

use Page\JsonForm;
use Page\SpinnerButton;
use Page\Modal;

class Create extends BaseOwnerDefinedCategoryPage
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
            'description' => JsonForm::FIELD_TYPES_TEXTAREA,
        ];
        $this->setEditForm(new JsonForm($I, '#owner-defined-category-create', $this->fieldTypes));
    }


    public function getUrl($params)
    {
        return parent::getUrl($params) . "/{$params['ownerId']}/defined/category/create";
    }

    public function saveForm()
    {
        $this->saveButton->click();
    }
}
