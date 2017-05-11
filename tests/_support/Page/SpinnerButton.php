<?php

namespace Page;

class SpinnerButton extends Button
{
    public function click()
    {
        parent::click();
        $this->tester->waitForElementNotVisible("{$this->selector} span.fa.active", 10);
    }
}
