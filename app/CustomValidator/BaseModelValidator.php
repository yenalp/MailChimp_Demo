<?php namespace App\CustomValidator;

use Illuminate\Http\Request;
use App\Exceptions\ModelValidationException;
use Illuminate\Support\Facades\Validator;

class BaseModelValidator
{
    protected $request;
    protected $model;

    // The request should be removed from this class as it is pointless here
    public function __construct(Request $request, $model = null)
    {
        $this->request = $request;
        $this->model = $model;
    }

    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }

    public function validateOrFail($request, $exceptionType)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if (!$validator->fails()) {
                return;
        }

        if ($exceptionType !== null) {
                throw new $exceptionType($validator);
        }
        throw new ModelValidationException($validator);
    }

    public function validate($data)
    {
        $validator = Validator::make($data, $this->rules(), $this->messages());
        if (!$validator->fails()) {
                return;
        }

        throw new ModelValidationException($validator);
    }
}
