<?php
namespace Helper;

use Illuminate\Support\Facades\Artisan;
use \Codeception\Util\Debug as Debug;

class BeforeAndAfter extends \Codeception\Module
{
    /**
     * Method is called before test file run
     */
     // @codingStandardsIgnoreStart
    public function _before(\Codeception\TestInterface $test)
    {
        Artisan::call('tool:clear', ['--env' => 'testing']);
        Artisan::call('migrate:refresh', ['--env' => 'testing']);
    }
    // @codingStandardsIgnoreEnd

    /**
     * Method is called after test file run
     */
    // @codingStandardsIgnoreStart
    public function _after(\Codeception\TestInterface $test)
    {
        Artisan::call('tool:clear', ['--env' => 'testing']);
    }
    // @codingStandardsIgnoreEnd
}
