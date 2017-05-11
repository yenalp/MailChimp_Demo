<?php
namespace App\Exceptions;

use Exception;
use App\Http\Requests\ApiError;

class BaseException extends Exception
{
    public $statusCode = null;
    public $frontEndMessage = '';
    public $backEndMessage = '';
    public $errorInstanceCode = null;

    public function __construct(
        $frontEndMessage = '',
        $backEndMessage = '',
        $statusCode = 500,
        $exception = null
    ) {
        $this->frontEndMessage = $frontEndMessage;
        $this->statusCode = $statusCode;
        $this->errorInstanceCode = strtoupper(bin2hex(random_bytes(2))) .'-'. strtoupper(bin2hex(random_bytes(2)));
        $this->frontEndMessage = "{$frontEndMessage}";
        $this->backEndMessage = "ERROR - {$this->errorInstanceCode} : {$backEndMessage}";

        // Check to see if an exception has been passed
        $message = $this->backEndMessage;
        $exceptionCode = $statusCode;
        $previous = $exception;

        if ($exception !== null) {
            $message = "{$this->backEndMessage} : {$exception->getMessage()}";
            $exceptionCode = $exception->getCode();
            $previous = $exception;
        }

        parent::__construct($message, $exceptionCode, $previous);
    }


    public function validateOrFail($request, $exceptionType)
    {
        Validator::make($request->all(), $this->rules(), $this->messages());
        parent::validateOrFail($request, $exceptionType);
    }
}
