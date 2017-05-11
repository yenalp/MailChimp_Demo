<?php

namespace Page\Admin\Owner;

use Page\Admin\BaseAdminPage;

abstract class BaseAdminOwnerPage extends BaseAdminPage
{
    public function getUrl($params)
    {
        return parent::getUrl($params) . '/owner';
    }
}
