<?php
namespace App\Models\Schemas;

use Mailchimp\MailChimp;

class MailChimpListsModel extends BaseMailChimpModel
{
    use \App\Traits\SecuredRelationships;

    public function __construct($mailChimpModel)
    {
        parent::__construct($mailChimpModel);
    }

    public static function lists($offset = 0, $count = 10)
    {
        $mc = new Mailchimp(env('MAILCHIMP_API_KEY'));
        $results = $mc->get('lists', [
            'fields' => 'lists.id,lists.name,lists.stats.member_count',
            'offset' => $offset,
            'count' => $count
        ]);

        $data = [];
        foreach ($results as $result) {
          $mailChimp = new MailChimpListsModel($result);
          array_push($data, $mailChimp);
        }

        return collect($data);
    }

    public static function listById($id)
    {
        $mc = new Mailchimp(env('MAILCHIMP_API_KEY'));
        $result = $mc->get("lists/${id}", [
            'fields' => 'lists.id,lists.name,lists.stats.member_count'
        ]);

        $data =[];
        $mailChimp = new MailChimpListsModel($result);
        array_push($data, $mailChimp);

        return collect($data);
    }

    public static function createList($list)
    {
        $data = [];
        $mc = new Mailchimp(env('MAILCHIMP_API_KEY'));
        $result = $mc->post('lists', $list);

        $mailChimp = new MailChimpListsModel($result->toArray());
        array_push($data, $mailChimp);

        return collect($data);
    }

}
