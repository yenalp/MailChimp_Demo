<?php
namespace App\Http\Formatters;

abstract class BaseFormatter
{
    public $serialiseFull = [];
    /**
    * @SuppressWarnings(PHPMD.UnusedFormalParameter)
    */
    public function serialise($model, $rawAttrs)
    {
        return $model->toArray();
    }

    public function deserialise($model, $data)
    {
        return $model->fill($data);
    }

    public function wrapResult($data)
    {
        return $data;
    }

    public function unwrapResult($data)
    {
        return $data;
    }
}
