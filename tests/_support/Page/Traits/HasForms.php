<?php
namespace Page\Traits;

trait HasForms
{

    public $alreadyFilled = [];

    public function fillFormFields($data, $form)
    {
        $cleaned = [];
        foreach ($data as $key => $val) {
            if (!in_array($key, $this->alreadyFilled)) {
                $cleaned[$key] = $val;
            }
        }
        $form->fillForm($cleaned);
    }

    public function fillFormField($name, $value, $form)
    {
        // $lCaseName = strtolower($name);
        // $fieldName = "root[{$lCaseName}]";
        $fieldName = $form->getFieldMapping($name);
        array_push($this->alreadyFilled, $fieldName);
        $form->fillField($name, $value);
    }

    public function selectOptionFormField($name, $value, $form)
    {
        $fieldName = $form->getFieldMapping($name);
        array_push($this->alreadyFilled, $fieldName);
        $form->selectOption($fieldName, $value);
    }


    public function clearFormField($name, $form)
    {
        // $lCaseName = strtolower($name);
        // $fieldName = "root[{$lCaseName}]";
        $form->clearField($fieldName);
    }

    public function fieldHasValue($name, $value, $form)
    {
        $form->checkFieldValue($name, $value);
    }


    public function fieldValue($name, $form)
    {
        return $form->getFieldValue($name);
    }
}
