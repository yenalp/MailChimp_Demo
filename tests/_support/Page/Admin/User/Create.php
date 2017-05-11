<?php
namespace Page\Admin\User;

use Page\JsonForm;

class Create extends Edit
{

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);
        $this->setEditForm(new JsonForm($I, '#user-create', $this->fieldTypes, $this->fieldMappings));
    }

    public function getUrl($params)
    {
        return parent::getUrl($params) . "/create";
    }
}
