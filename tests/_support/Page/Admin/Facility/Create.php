<?php
namespace Page\Admin\Facility;

class Create
{
    // include url of current page
    public static $URL = '#/facility/create';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    public static $formId = "facility-create";
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

    protected $fieldSelectors;

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
        ];
    }

    public function fillField($name, $value)
    {
        $formId = self::$formId;
        $this->tester->seeInCurrentUrl('/facility/create');
        $this->tester->waitForElement($this->fieldSelectors[$name]);
        $this->tester->fillField($this->fieldSelectors[$name], $value);

        // Unfocus field to trigger model change
        $this->tester->pressKey($this->fieldSelectors[$name], \Facebook\WebDriver\WebDriverKeys::TAB);
        $this->tester->click("#{$formId}");
    }

    public function selectOption($name, $value)
    {
        $this->tester->selectOption($this->fieldSelectors[$name], $value);
        $this->tester->wait(1);
    }

    public function appendField($name, $value)
    {
        $formId = self::$formId;
        $this->tester->appendField($this->fieldSelectors[$name], $value);
        $this->tester->pressKey($this->fieldSelectors[$name], \Facebook\WebDriver\WebDriverKeys::TAB);
        $this->tester->click("#{$formId}");
    }

    public function clickAddBenchmarkBtn()
    {
        $addBenchmarkBtnText = self::$addBenchmarkBtnText;
        $this->tester->click($addBenchmarkBtnText);
    }

    public function saveForm()
    {
        $this->tester->click(self::$saveBtnSelector);
    }

    public function checkBenchmarkNumbers($createdFacility, $benchmark_field, $value, $year)
    {
        $this->tester->assertTrue($createdFacility->benchmark_numbers[0]["benchmark_field"] === $benchmark_field);
        $this->tester->assertTrue($createdFacility->benchmark_numbers[0]["benchmark"] === (int)$value);
        $this->tester->assertTrue($createdFacility->benchmark_numbers[0]["year"] === $year);
    }

    public function checkNumberOfFacilities($expected, $actual)
    {
        $this->tester->assertTrue($expected === $actual);
    }

    public function seeValidationMessage($validationType)
    {
        if ($validationType === 'min_number') {
            $this->tester->seeElement('.errormsg');
            $this->tester->see('Value must be at least 1');
        } elseif ($validationType === 'max_number') {
            $this->tester->seeElement('.errormsg');
            $this->tester->see('Value must be at most 2147483647');
        } elseif ($validationType === 'max_year') {
            $this->tester->seeElement('.errormsg');
            $this->tester->see('Value must be less than or equal to the current year');
        } elseif ($validationType === 'unique') {
            $this->tester->see('Value for benchmark numbers must be unique across year, benchmark field');
        }
        $this->tester->wait(2);
    }
}
