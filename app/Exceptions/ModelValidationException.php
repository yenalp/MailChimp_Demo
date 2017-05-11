<?php
namespace App\Exceptions;

class ModelValidationException extends BaseException
{

    public $validator;

    public function __construct($validator)
    {
        $this->validator = $validator;
        $frontEndMessage = "Form validation error.";
        $backEndMessage = "Form validation error.";

        parent::__construct($frontEndMessage, $backEndMessage, 422, null);
    }
}
