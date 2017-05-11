<?php
namespace Page;

use Page\Modal;

abstract class BasePage
{
    public $tester;
    public $modal;

    public function __construct(\Codeception\Actor $I)
    {
        $this->tester = $I;
        $this->modal = new Modal($I, 'global-modal');
    }

    public function amOnThePage($params)
    {
        $this->tester->seeInCurrentUrl($this->getUrl($params));
    }

    public function getUrl($params)
    {
        return '/#';
    }

    public function clearStateInfo()
    {
        $this->stateInfo = [];
    }

    public function modalErrorIsVisible()
    {
        $this->modal->waitUntilLoaded();
    }

    public function modalErrorContains($text)
    {
        $this->modal->waitUntilLoaded();
        $this->modal->seeInModal($text);
    }
}
