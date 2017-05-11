<?php

namespace App\Http\Formatters;

use App\Http\Formatters\BaseFormatter;

class SerialisableCollection
{
    public $models;

    public function __construct($models)
    {
        $this->models = $models;
    }

    public function serialise(BaseFormatter $formater)
    {
        $data = [];
        foreach ($this->models as $model) {
            array_push($data, $model->serialise($formater));
        }

        return $data;
    }
}
