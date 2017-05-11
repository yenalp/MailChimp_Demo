<?php

namespace Page;

abstract class BaseClickable
{

    public $selector;
    public $tester;

    public function __construct(\Codeception\Actor $I, $selector)
    {
        $this->selector = $selector;
        $this->tester = $I;
    }

    public function waitUntilLoaded($timeout = 10)
    {
        // $this->tester->waitForElement("{$this->selector}", $timeout);
        // $this->tester->wait(1);
        $this->tester->waitForElementVisible("{$this->selector}", $timeout);
    }

    public function click()
    {
        $this->tester->click($this->selector);
    }
}
