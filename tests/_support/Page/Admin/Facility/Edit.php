<?php
namespace Page\Admin\Facility;

class Edit
{
    // include url of current page
    public static $URL = '#/facility/edit';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    public static $formId = "facility-update";
    public static $deleteBenchmarkFormId = "root-editor";
    public static $addBenchmarkBtnText = "Add Benchmark Number";
    public static $saveBtnSelector = "#editor-save";

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
        $formId = self::$formId;
        $this->tester = $I;

        $this->fieldSelectors = [
            'name' => "#{$formId} input[name=\"root[name]\"]",
            'service_level' => "select[name=\"root[service_level]\"]",
            'benchmark_year' => "select[name=\"root[benchmark_numbers][0][year]\"]",
            'benchmark_field' => "select[name=\"root[benchmark_numbers][0][benchmark_field]\"]",
            'benchmark_number' => "input[name=\"root[benchmark_numbers][0][benchmark]\"]",
            'benchmark_year_2' => "select[name=\"root[benchmark_numbers][1][year]\"]",
            'benchmark_field_2' => "select[name=\"root[benchmark_numbers][1][benchmark_field]\"]",
            'benchmark_number_2' => "input[name=\"root[benchmark_numbers][1][benchmark]\"]",
            'benchmark_delete_reason' => "input[name=\"root[item_delete_log_reason]\"]"
        ];
    }

    public function fillField($name, $value)
    {
        $formId = self::$formId;
        $this->tester->seeInCurrentUrl('/facility/edit');
        $this->tester->waitForElement($this->fieldSelectors[$name], 30);

        if ($name === 'benchmark_number') {
            $this->tester->fillField($this->fieldSelectors[$name], 10);
            $this->tester->pressKey($this->fieldSelectors[$name], \Facebook\WebDriver\WebDriverKeys::TAB);
            $this->tester->click("#{$formId}");
        }

        $this->tester->fillField($this->fieldSelectors[$name], $value);

        // Unfocus field to trigger model change
        $this->tester->pressKey($this->fieldSelectors[$name], \Facebook\WebDriver\WebDriverKeys::TAB);
        $this->tester->click("#{$formId}");
    }

    public function selectOption($name, $value)
    {
        $this->tester->waitForElement($this->fieldSelectors[$name]);
        $this->tester->selectOption($this->fieldSelectors[$name], $value);
        $this->tester->wait(1);
    }

    public function clickEditFacilityBtn()
    {
        $containerId = self::$containerId;
        $this->tester->waitForElement("#{$containerId}");
        $editBtnText = self::$editBtnText;
        $this->tester->click($editBtnText);
    }

    public function annualBenchmarkExists()
    {
        $this->tester->waitForElement($this->fieldSelectors['benchmark_year'], 30);
        $this->tester->seeElement($this->fieldSelectors['benchmark_year']);
        $this->tester->seeElement($this->fieldSelectors['benchmark_field']);
        $this->tester->seeElement($this->fieldSelectors['benchmark_number']);
    }

    public function removeBenchmarkNumber()
    {
        $this->tester->click('Delete');
    }

    public function iShouldBePromptedForAReason()
    {
        $this->tester->waitForElement('//*[@id="benchmark-delete-modal"]/div/div/div[1]/h4', 6);
        $this->tester->see('Reason');
    }

    public function benchmarkNumberDeleted($facility)
    {
        $this->tester->assertTrue(count($facility->benchmark_numbers) === 0);
    }

    public function enterDeleteBenchmarkReason()
    {
        $deleteBenchmarkFormId = self::$deleteBenchmarkFormId;
        $this->tester->click($this->fieldSelectors['benchmark_delete_reason']);
        $this->tester->fillField($this->fieldSelectors['benchmark_delete_reason'], 'Test');
        $this->tester->pressKey(
            $this->fieldSelectors['benchmark_delete_reason'],
            \Facebook\WebDriver\WebDriverKeys::TAB
        );
        $this->tester->click("#{$deleteBenchmarkFormId}");

        $this->tester->click($this->fieldSelectors['benchmark_delete_reason']);
        $this->tester->fillField($this->fieldSelectors['benchmark_delete_reason'], 'Test Deleting Benchmark');
        $this->tester->pressKey(
            $this->fieldSelectors['benchmark_delete_reason'],
            \Facebook\WebDriver\WebDriverKeys::TAB
        );
        $this->tester->click("#{$deleteBenchmarkFormId}");
    }

    public function cancelDeleteTheAnnualBenchmarkNumber()
    {
        $this->tester->click('//*[@id="json-editor"]/button[2]');
    }

    public function errorShows()
    {
        $this->tester->seeElement('.has-danger');
    }
}
