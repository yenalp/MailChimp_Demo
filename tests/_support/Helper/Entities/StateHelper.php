<?php
namespace Helper\Entities;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
// See here for details: http://codeception.com/docs/10-WebServices#REST

class StateHelper extends \Codeception\Module
{

    public $stateInfo = [];

    public function setStateInfo($name, $value)
    {
        $this->stateInfo[strtolower($name)] = $value;
    }

    public function getStateInfo()
    {
        return $this->stateInfo;
    }

    public function clearStateInfo()
    {
        $this->stateInfo = [];
    }

    public function stateInfoFindOrFail($name)
    {
        if (isset($this->stateInfo[strtolower($name)])) {
            return $this->stateInfo[$name];
        }
        return '';
    }
}
