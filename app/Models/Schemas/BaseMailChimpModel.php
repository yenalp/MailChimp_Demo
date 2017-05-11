<?php
namespace App\Models\Schemas;

use App\Traits\KeyPath;
use App\Exceptions\MailChimpException;
use App\Traits\ApiSerialisableInterface;
use App\Http\Formatters\BaseFormatter;

abstract class BaseMailChimpModel implements ApiSerialisableInterface
{
    use KeyPath;
    use \App\Traits\ApiSerialisable;

    public $mc = null;

    public function __construct($mailChimpModel)
    {
        $this->mc = $mailChimpModel;
    }

    public function update()
    {
    }

    public function get()
    {
    }

    public function save()
    {
    }

    public function serialise(BaseFormatter $formatter)
    {
        $data = [
            'id' => $this->getKp('mc.id'),
            'name' => $this->getKp('mc.name'),
            'member_count' => $this->getKp('mc.stats.member_count')
        ];
        $attributes = $formatter->serialise($this, $data);
        return array_merge($attributes, $this->serialiseRelations($formatter));
    }

    public function serialiseRelations(BaseFormatter $formatter)
    {
        return [];
    }

    public function onBeforeSave()
    {
        //
    }

    public function onAfterSave()
    {
        //
    }
}
