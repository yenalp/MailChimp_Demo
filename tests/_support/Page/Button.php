<?php

namespace Page;

class Button extends BaseClickable
{
    public function isVisableOnPage()
    {
        $this->tester->seeElement($this->selector);
    }

    public function isNotVisableOnPage()
    {
        $this->tester->dontSeeElement($this->selector);
    }

    public function isOnPage()
    {
        $this->tester->seeElementInDOM($this->selector);
    }
}
