<?php
namespace Page\Traits;

trait ViewPage
{

    public $editButton;

    public function edit()
    {
        $this->editButton->click();
    }

    public function iCanSeeOnPageByName($name, $text)
    {
        $nameDisplay = ucwords(str_replace('_', ' ', $name));
        return $this->tester->see("{$nameDisplay}: {$text}");
    }
}
