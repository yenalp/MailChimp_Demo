<?php namespace App\Services;

use App\Services\BaseService;
use App\Exceptions\MailChimpException;
use App\Models\Schemas\MailChimpListsModel;
use App\Models\Schemas\MailChimpMembersModel;

class MailChimpService extends BaseService
{
    public function lists($offset = 0, $count = 10)
    {
        return MailChimpListsModel::lists($offset, $count);
    }

    public function createList($list)
    {
        $this->validate($list, null);
        return MailChimpListsModel::createList($list);
    }

    public function createListMember($id, $member)
    {
        return MailChimpMembersModel::createListMember($id, $member);
    }

    public function updateListMember($id, $memberId, $member)
    {
        return MailChimpMembersModel::updateListMember($id, $memberId, $member);
    }
}
