<?php

namespace Page\Admin\Owner;
    
use Page\JsonForm;

class Create extends Edit
{

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);
        $this->setEditForm(new JsonForm($I, '#owner-create', $this->fieldTypes));
    }

    public function getUrl($params)
    {
        return parent::getUrl($params) . "/create";
    }
}
