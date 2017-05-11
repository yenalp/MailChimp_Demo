<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Exceptions\BaseException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
     public function render($request, Exception $e)
     {
         $currentUrl = $request->url();
         $title = 'Error';
         $msg = 'The API request has failed.';
         $errors = [
             'status' => 500,
             'source' => ['pointer' => $currentUrl],
             'title' => $title,
             'message' => $msg
         ];

         $errors['error_id'] = isset($e->errorInstanceCode) ?
             $e->errorInstanceCode : strtoupper(bin2hex(random_bytes(2))) .'-'. strtoupper(bin2hex(random_bytes(2)));
         $errors['error_message'] = isset($e->frontEndMessage) ? $e->frontEndMessage : $e->getMessage();
         $errors['error_type'] = 'ERROR';
         $errors['status'] = 500;
         $errors['error_code'] = $e->getCode();
         $errors['error_description'] = '';
         $errors['description'] = '';
         $errors['validation_errors'] = [
             "errors" => [],
             "attributes" => []
         ];

         if ($e instanceof BaseException) {
             $errors['status'] = $e->statusCode;
             $errors['error_description'] = 'An error has occurred';
             $errors['error_type'] = 'FATAL';
         } elseif ($e instanceof \Symfony\Component\Debug\Exception\FatalThrowableError) {
             $errors['message'] = $e->getMessage();
             $errors['status'] = 500;
             $errors['error_description'] = 'Fatal Throwable Error';
             $errors['error_type'] = 'FATAL';
         } elseif ($e instanceof \Illuminate\Database\QueryException) {
             $errors['status'] = 500;
             $errors['error_description'] = 'Query Exception';
             $errors['error_type'] = 'FATAL_QUERY';
         } elseif ($e instanceof \NotFoundException
             || $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException
         ) {
             $errors['message'] = $e->getMessage();
             $errors['status'] = 404;
             $errors['error_description'] = 'Not Found';
             $errors['error_type'] = 'NOT_FOUND';
             $errors['error_message'] = 'The requested operation failed because a resource associated '
                 .' with the request could not be found';
         } elseif ($e instanceof \ErrorException) {
             $errors['status'] = 500;
             $errors['error_type'] = 'ERROR_EXCEPTION';
         } elseif ($e instanceof \Exception) {
             $errors['status'] = 500;
             $errors['error_type'] = 'EXCEPTION';
             $errors['message'] = $e->getMessage();
             $errors['status'] = 500;
             $errors['error_description'] = 'Error';
         }

         $errorCodes = $this->errorCodes($errors['status']);
         if ($errorCodes !== null) {
             $errors['title'] = $errorCodes['title'];
             $errors['description'] = $errorCodes['message'];
         }

         //Build Response string
         $response = [
             'error' => $errors
         ];

         return response()->json($response, $errors['status'], ['Content-Type' => 'application/json']);
     }

     protected function errorCodes($code)
     {
         $errorCode = [

             '204' => [
                 'title' => 'No Content',
                 'message' => 'The request failed due to no content.'
             ],
             '400' => [
                 'title' => 'Bad Request',
                 'message' => 'The API request is invalid or improperly formed. Consequently, '
                         .' the API server could not understand the request',
             ],
             '401' => [
                 'title' => 'Unauthorized',
                 'message' => 'The user is not authorized to make the request.'
             ],
             '402' => [
                 'title' => 'Forbidden',
                 'message' => 'The requested operation is forbidden and cannot be completed'
             ],
             '404' => [
                 'title' => 'Not Found',
                 'message' => 'The requested operation failed because a resource associated '
                     .' with the request could not be found'
             ],
             '405' => [
                 'title' => 'Method Not Allowed',
                 'message' => 'The HTTP method associated with the request is not supported'
             ],
             '409' => [
                 'title' => 'Conflict',
                 'message' => 'The requested operation failed because it tried to create a resource that already exists.'
             ],
             '410' => [
                 'title' => 'Gone',
                 'message' => 'The request failed because the resource associated with the request has been deleted'
             ],
             '422' => [
                 'title' => 'Unprocessable Entity',
                 'message' => 'The request was well-formed but was unable to be followed due to semantic errors'
             ],
             '500' => [
                 'title' => 'Internal Error',
                 'message' => 'The request failed due to an internal error.'
             ]
         ];

         return isset($errorCode[$code]) ? $errorCode[$code] : null ;
     }
}
