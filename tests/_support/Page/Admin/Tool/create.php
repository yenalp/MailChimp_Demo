<?php
namespace Page\Admin\Tool;

use Illuminate\Support\Facades\Storage;

class Create
{
    // include url of current page
    public static $URL = '#/tool/create';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    public static $formId = "tool-create";
    public static $addBenchmarkBtnText = "Save New Tool";
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
            'title' => "#{$formId} input[name=\"root[title]\"]",
            'description' => "#{$formId} Text-area[name=\"root[description]\"]"
        ];
    }

    public function fillField($name, $value)
    {

        if ($name == 'title') {
            $valueNew = explode(' ', $value);
            $value = $valueNew[0] . ' ';
        }

        $formId = self::$formId;
        $this->tester->seeInCurrentUrl('/tool/create');
        $this->tester->waitForElement($this->fieldSelectors[$name]);
        $this->tester->fillField($this->fieldSelectors[$name], $value);

        // Unfocus field to trigger model change
        $this->tester->pressKey($this->fieldSelectors[$name], \Facebook\WebDriver\WebDriverKeys::TAB);
        $this->tester->click("#{$formId}");

        if ($name == 'title') {
            unset($valueNew[0]);
            $value =  implode(' ', $valueNew);
            $this->appendField('title', $value);
        }
    }

    public function saveForm()
    {
        $this->tester->click(self::$saveBtnSelector);
        $this->tester->wait(3);
    }

    public function appendField($name, $value)
    {
        $formId = self::$formId;
        $this->tester->appendField($this->fieldSelectors[$name], $value);
        $this->tester->pressKey($this->fieldSelectors[$name], \Facebook\WebDriver\WebDriverKeys::TAB);
        $this->tester->click("#{$formId}");
    }

    public function theToolVersionShouldBe($field, $value)
    {
            $schema = json_decode(Storage::disk('local')->get('tools/tools_8/1.0.0/schema.json'));
            $options = collect($schema)->get('options');
            $versionMajor = $options->version->major;
            $versionMinor = $options->version->minor;
            $versionPatch = $options->version->patch;
            $version = $versionMajor .'.'. $versionMinor .'.'. $versionPatch;

            $versionCorrect = false;
        if ($value === $version) {
            $versionCorrect = true;
        }
            $this->tester->assertTrue($versionCorrect);
    }

    public function theToolStatusShouldBe($field, $value)
    {
            $schema = json_decode(Storage::disk('local')->get('tools/tools_8/1.0.0/schema.json'));
            $options = collect($schema)->get('options');
            $status = $options->version->status;

            $statusCorrect = false;
        if ($value === $status) {
            $statusCorrect = true;
        }
            $this->tester->assertTrue($statusCorrect);
    }
}
