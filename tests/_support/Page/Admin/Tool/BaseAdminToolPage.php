<?php
namespace Page\Admin\Tool;

use Page\Admin\BaseAdminPage;

abstract class BaseAdminToolPage extends BaseAdminPage
{
    public function getUrl($params)
    {
        return parent::getUrl($params) . '/tool';
    }
}
