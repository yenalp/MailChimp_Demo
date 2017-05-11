<?php
namespace App\Traits;

use App\Http\Formatters\BaseFormatter;

interface ApiSerialisableInterface
{
    public function serialise(BaseFormatter $formatter);
    public function serialiseMinimal(BaseFormatter $formatter);
    public function serialiseRelations(BaseFormatter $formatter);
}
