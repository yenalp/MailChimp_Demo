<?php
namespace Page\Admin;

use Page\BasePage;

abstract class BaseAdminPage extends BasePage
{
    public $hasSpinner = true;
    public $form;

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public function waitUntilLoaded($timeout = 10)
    {
        if ($this->hasSpinner) {
            $this->tester->waitForElementNotVisible('.fa-spinner', $timeout);
        }
    }

    public function getUrl($params)
    {
        // This is where you can easily update the admin site
        // base URL in v0.0.6 when it is located in a subfolder.
        return  '/admin' . parent::getUrl($params);
    }

    public function grabTextFromElementById($id)
    {
        return $this->tester->grabTextFrom($id);
    }
}
