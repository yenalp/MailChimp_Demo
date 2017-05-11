<?php
namespace App\Http\Formatters;

class FormatterFactory
{
    public static function getFormatter($name)
    {
        switch ($name) {
            case 'attributes':
                return new ApiFormatter();
                break;
            case 'v1':
                return new DefaultFormatter();
                break;
            default:
                return new DefaultFormatter();
        }
    }
}
