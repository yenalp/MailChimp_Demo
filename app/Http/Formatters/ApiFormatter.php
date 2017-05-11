<?php
namespace App\Http\Formatters;

class ApiFormatter extends BaseFormatter
{
    use \App\Traits\KeyPath;

    public function serialise($model, $rawAttrs)
    {
        $attributes = [
            "id" => $rawAttrs['id'],
            "type" => class_basename($model),
            "attributes" => $rawAttrs
        ];
        return $attributes;
    }

    public function deserialise($model, $data)
    {
        return $model->fill($data);
    }

    public function wrapResult($data)
    {
        return [
            'data' => $data,
            'jsonapi' => [
                'version' => env('VERSION_API', '0.0.1'),
                'format' => 'attributes'
            ]
        ];
    }

    public function unwrapResult($data)
    {
        return getKp($data, 'data.attributes');
    }
}
