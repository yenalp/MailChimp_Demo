<?php
namespace Page\Admin\User;

use Page\DataTable;
use Page\Button;

class View extends BaseAdminUserPage
{
    use \Page\Traits\ViewPage;

    // include url of current page
    public static $URL = '#/user';
    public static $containerId = "#user";

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);
        $containerId = self::$containerId;
        $this->fieldMappings = [
            'first name' => [
                'label' => 'Name:',
                'ignore'=> false,
            ],
            'last name' => [
                'label' => '',
                'ignore'=> false,
            ],
            'username' => [
                'label' => 'User Name:',
                'ignore'=> false,
            ],
            'password' => [
                'label' => 'password',
                'ignore'=> true,
            ],
            'confirm password' => [
                'label' => 'confirm password',
                'ignore'=> true,
            ],
            'email' => [
                'label' => 'Email:',
                'ignore'=> false,
            ],
            'contact number' => [
                'label' => 'Contact Number:',
                'ignore'=> false,
            ],

            'user type' => [
                'label' => 'User Type:',
                'ignore'=> false,
            ],
        ];
    }

    public function waitUntilLoaded($timeout = 10)
    {
        parent::waitUntilLoaded($timeout);
    }

    public function getUrl($params)
    {
        return parent::getUrl($params) . "/{$params['userId']}";
    }
}
