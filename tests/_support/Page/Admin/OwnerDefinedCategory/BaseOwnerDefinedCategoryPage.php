<?php

namespace Page\Admin\OwnerDefinedCategory;

use Page\Admin\Owner\BaseAdminOwnerPage;

abstract class BaseOwnerDefinedCategoryPage extends BaseAdminOwnerPage
{
    public function getUrl($params)
    {
        return parent::getUrl($params);
    }
}
