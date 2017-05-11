<?php
namespace Page;

use Codeception\Util\Locator;
use Codeception\Util\PhpBrowser;
use Symfony\Component\DomCrawler\Crawler;

class JsonForm
{

    public $elementSelector;
    protected $tester;
    public $fieldTypes = [];
    public $fieldMappings = [];
    public $editorSelector;
    public $fieldNameWrapper;

    const FIELD_TYPES_TEXT =  'text';
    const FIELD_TYPES_TEXTAREA =  'textarea';
    const FIELD_TYPES_SELECT = 'select';
    const FIELD_TYPES_RADIO = 'radio';
    const FIELD_TYPES_CHECKBOX = 'checkbox';

    public $fieldTypeMappings = [
        'text' => 'fillField',
        'textarea' => 'fillField',
        'radio' => 'selectOption',
        'select' => 'selectOption',
        'checkbox' => 'checkOption'
    ];

    /**
     * @var \Symfony\Component\DomCrawler\Crawler
     */
    protected $crawler;

    public function __construct(
        \Codeception\Actor $I,
        $elementSelector,
        $fieldTypes = [],
        $fieldMappings = [],
        $editorSelector = '#root-editor'
    ) {

        $this->tester = $I;
        $this->elementSelector = $elementSelector;
        $this->fieldTypes = $fieldTypes;
        $this->fieldMappings = $fieldMappings;
        $this->editorSelector = $editorSelector;
        $this->fieldNameWrapper = 'root[{{__field__}}]';
    }

    public function waitUntilLoaded()
    {
        $this->tester->waitForElement("{$this->elementSelector} $this->editorSelector", 20);
        $this->tester->waitForElement("{$this->elementSelector} $this->editorSelector.editor-loaded", 20);
    }

    /**
     * Fills in Text boxs and Textarea
     */
    public function fillField($name, $value)
    {
        $result = str_split($value);
        $fieldName = $this->getFieldMapping($name);
        $wrappedName = str_replace('{{__field__}}', $fieldName, $this->fieldNameWrapper);
        foreach ($result as $char) {
            $this->tester->pressKey("[name=\"{$wrappedName}\"]", $char);
        }
    }

    public function clearField($name)
    {
        $fieldName = $this->getFieldMapping($name);
        $wrappedName = str_replace('{{__field__}}', $fieldName, $this->fieldNameWrapper);
        $this->tester->fillField("[name=\"{$wrappedName}\"]", '');
    }

    /**
     * Sets option for selects and radio buttons
     */
    public function selectOption($name, $value)
    {
        $wrappedName = str_replace('{{__field__}}', $name, $this->fieldNameWrapper);
        $this->tester->selectOption(['name' => "{$wrappedName}"], $value);
    }

    /**
     * ticks a checkbox
     */
    public function checkOption($name, $value = '')
    {
        $wrappedName = str_replace('{{__field__}}', $name, $this->fieldNameWrapper);
        $this->tester->selectOption($wrappedName);
    }

    public function fillForm($data)
    {
        foreach ($data as $field => $value) {
            if (!array_key_exists($field, $this->fieldTypes)) {
                continue;
            }
            $fieldName = $this->getFieldMapping($field);

            $fieldType = $this->fieldTypes[$fieldName];
            $fillFieldMethod = $this->fieldTypeMappings[$fieldType];
            $this->$fillFieldMethod($fieldName, $value);
        }
    }

    public function checkFieldValue($field, $value)
    {
        $fieldName = str_replace('{{__field__}}', $field, $this->fieldNameWrapper);
        $this->tester->seeInField($fieldName, $value);
    }


    public function getFieldValue($field)
    {
        $fieldName = str_replace('{{__field__}}', $field, $this->fieldNameWrapper);
        return $this->tester->grabValueFrom($fieldName);
    }


    public function removeSpaceFromFieldForm($field, $deliminator = '_')
    {
        return str_replace(' ', $deliminator, $field);
    }

    public function getFieldMapping($field)
    {
        $newField = strtolower($field);
        if (isset($this->fieldMappings[$newField])) {
            return $this->fieldMappings[$newField]['field_name'];
        }

        return $this->removeSpaceFromFieldForm($field);
    }
}
