<?php
namespace App\Exceptions;

class NotAuthorisedException extends BaseException
{
    public function __construct($frontEndMessage, $backEndMessage, $exception = null)
    {
        parent::__construct($frontEndMessage, $backEndMessage, 401, $exception);
    }
}
