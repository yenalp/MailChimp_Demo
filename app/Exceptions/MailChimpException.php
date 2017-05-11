<?php
namespace App\Exceptions;

class MailChimpException extends BaseException
{
    public function __construct($frontEndMessage = '', $backEndMessage = '', $statusCode = 500, $exception = null)
    {
        parent::__construct($frontEndMessage, $backEndMessage, $statusCode, $exception);
    }
}
