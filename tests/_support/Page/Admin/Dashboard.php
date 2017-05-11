<?php
namespace Page\Admin;

class Dashboard extends BaseAdminPage
{
    // include url of current page
    public static $URL = '/';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

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
        $this->tester = $I;
    }

    public function sidebarNavLink($text)
    {
        $this->tester->waitForElementVisible('.sidebar', 10);
        $this->tester->waitForElementVisible('.sidebar-nav', 10);
        $this->tester->click($text, '.sidebar-nav');
        // $this->tester->waitForText($text, 10, '.sidebar .nav-link');
        $this->tester->wait(2);
    }
}
