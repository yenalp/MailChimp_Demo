<?php

namespace Page\Admin\User;

use Page\Admin\BaseAdminPage;

abstract class BaseAdminUserPage extends BaseAdminPage
{
    public $fieldMappings;

    public function getUrl($params)
    {
        return parent::getUrl($params) . '/user';
    }

    public function getFieldMapping($field)
    {
        $newField = strtolower($field);
        if (isset($this->fieldMappings[$newField])) {
            return $this->fieldMappings[$newField];
        }

        return $this->removeSpaceFromField($field);
    }
}
