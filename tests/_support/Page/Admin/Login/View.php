<?php
namespace Page\Admin\Login;

use App\Models\ActivityLog;
use Page\Admin\BaseAdminPage;

class View extends BaseAdminPage
{
    // include url of current page
    public static $URL = 'admin/#/login';

    public static $containerId = "#login";
    public $tableId = "table-owners";

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);
        $containerId = self::$containerId;
    }

    public function iEnterAValidUsername($userName)
    {
        $this->tester->fillField('user_name', $userName);
    }

    public function iEnterAValidPassword($password)
    {
        $this->tester->fillField('password', $password);
    }

    public function iLogIn()
    {
        $this->tester->click('Login');
        $this->tester->wait(2);
    }

    public function anEntryIsAddedToTheActivityLog($message)
    {
        $activityLogs = ActivityLog::get();
        $description = $activityLogs->search(function ($item, $key) use ($message) {
            return $item['attributes']['description'] === $message;
        }, true);

        if ($description !== false) {
            $this->tester->assertTrue(true);
        }
    }

    public function iShouldSeeAnErrorMessage()
    {
        $this->tester->see('Error');
    }


    public function iLogOut()
    {
        $this->tester->wait(2);
        $this->tester->waitForElement('#nav-logout', 6);
        $this->tester->click('#nav-logout');
        $this->tester->wait(2);
    }
}
