<?php
namespace Page\Traits;

trait IsEditPage
{

    public $editForm;

    public function fillEditField($name, $value)
    {
        $this->fillFormField($name, $value, $this->editForm);
    }

    public function selectOptionField($name, $value)
    {
        $this->selectOptionFormField($name, $value, $this->editForm);
    }

    public function fillEditForm($data)
    {
        $this->fillFormFields($data, $this->editForm);
    }

    public function formFieldHasValue($field, $value)
    {
        $this->fieldHasValue($field, $value, $this->editForm);
    }

    public function setEditForm($form)
    {
        $this->editForm = $form;
    }

    public function clearEditField($name)
    {
        $this->clearFormField($name, $this->editForm);
    }

    public function removeSpaceFromField($field)
    {
        return $this->editForm->removeSpaceFromFieldForm($field);
    }

    public function formFieldValue($field)
    {
        return $this->fieldValue($field, $this->editForm);
    }
}
