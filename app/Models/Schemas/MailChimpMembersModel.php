<?php
namespace App\Models\Schemas;

use Mailchimp\MailChimp;
use App\Http\Formatters\BaseFormatter;

class MailChimpMembersModel extends BaseMailChimpModel
{
    use \App\Traits\SecuredRelationships;

    public function __construct($mailChimpModel)
    {
        parent::__construct($mailChimpModel);
    }


    public static function memberBy($id)
    {
        $data = [];
        $mc = new Mailchimp(env('MAILCHIMP_API_KEY'));
        $result = $mc->post("lists/{$id}/members", $member);

        $mailChimp = new MailChimpMembersModel($result->toArray());
        array_push($data, $mailChimp);

        return collect($data);
    }

    public static function createListMember($id, $member)
    {
        $data = [];
        $mc = new Mailchimp(env('MAILCHIMP_API_KEY'));
        $result = $mc->post("lists/{$id}/members", $member);

        $mailChimp = new MailChimpMembersModel($result->toArray());
        array_push($data, $mailChimp);

        return collect($data);
    }

    public static function updateListMember($id, $memberId, $member)
    {
        $data = [];
        $mc = new Mailchimp(env('MAILCHIMP_API_KEY'));
        $result = $mc->patch("lists/{$id}/members/{$memberId}", $member);

        $mailChimp = new MailChimpMembersModel($result->toArray());
        array_push($data, $mailChimp);

        return collect($data);
    }

    public function serialise(BaseFormatter $formatter)
    {
        $data = [
            'id' => $this->getKp('mc.id'),
            'first_name' => $this->getKp('mc.merge_fields.FNAME'),
            'last_name' => $this->getKp('mc.merge_fields.LNAME'),
            'email_address' => $this->getKp('mc.email_address')
        ];
        $attributes = $formatter->serialise($this, $data);
        return array_merge($attributes, $this->serialiseRelations($formatter));
    }
}
