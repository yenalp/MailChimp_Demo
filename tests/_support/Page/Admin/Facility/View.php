<?php
namespace Page\Admin\Facility;

class View
{
    // include url of current page
    public static $URL = '#/facility';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    public static $containerId = "facility";
    public static $editBtnText = "Edit Facility";
    // public static $saveBtnSelector = "#editor-save";

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    /**
     * @var AcceptanceTester
     */
    protected $tester;

    public function __construct(\AdminAcceptanceTester $I)
    {
        $containerId = self::$containerId;
        $this->tester = $I;
    }

    public function clickEditFacilityBtn()
    {
        $containerId = self::$containerId;
        $this->tester->waitForElement("#{$containerId}");
        $editBtnText = self::$editBtnText;
        $this->tester->click($editBtnText);
        // $this->tester->wait(5);
    }
}
