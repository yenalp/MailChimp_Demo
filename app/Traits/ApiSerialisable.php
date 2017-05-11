<?php
namespace App\Traits;

use App\Http\Formatters\BaseFormatter;
use App\Models\CustomBuilder;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

trait ApiSerialisable
{

    public function newEloquentBuilder($query)
    {
        return new CustomBuilder($query);
    }

    public function serialise(BaseFormatter $formatter)
    {
        $attributes = $formatter->serialise($this, $this->attributesToArray());
        return array_merge($attributes, $this->serialiseRelations($formatter));
    }

    // By Default this just calls serialise but you can override it in
    // an individual class if you want to resturn a subset of properties
    public function serialiseMinimal(BaseFormatter $formatter)
    {
        return $this->serialise($formatter);
    }

    public function serialiseRelations(BaseFormatter $formatter)
    {
        $attributes = [];

        foreach ($this->getArrayableRelations() as $key => $value) {
            // If the relationships snake-casing is enabled, we will snake case this
            // key so that the relation attribute is snake cased in this returned
            // array to the developers, making this consistent with attributes.
            if (static::$snakeAttributes) {
                $key = Str::snake($key);
            }

            if (is_null($value)) {
                $attributes[$key] = $value;
                continue;
            }

            if (!$value instanceof Arrayable) {
                // If the relation value has been set, we will set it on this attributes
                // list for returning. If it was not arrayable or null, we'll not set
                // the value on the array because it is some type of invalid value.
                $attributes[$key] = null;
                continue;
            }

            if ($value instanceof ApiSerialisableInterface) {
                $attributes[$key] = $value->serialise($formatter);
                continue;
            }

            if (is_array($value) || $value instanceof Collection) {
                $attributes[$key] = $this->serialiseCollection($value, $formatter);
                continue;
            }

            $attributes[$key] = $value->toArray();
            continue;
        }

        return $attributes;
    }

    protected function serialiseCollection($arr, $formatter)
    {
        $relation = [];
        foreach ($arr as $item) {
            if ($item instanceof ApiSerialisableInterface) {
                array_push($relation, $item->serialise($formatter));
                continue;
            }
            array_push($relation, $item);
        }
        return $relation;
    }
}
