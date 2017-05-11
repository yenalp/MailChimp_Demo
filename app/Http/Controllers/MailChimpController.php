<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MailChimpService;
use App\Http\Formatters\SerialisableCollection;

class MailChimpController extends BaseController
{
    public function __construct(Request $request, MailChimpService $service)
    {
        parent::__construct($request, $service);
    }

    public function lists()
    {
      return $this->res(new SerialisableCollection($this->service->lists()));
    }

    public function createList(Request $request)
    {
      $list = $request->toArray();
      return $this->res(new SerialisableCollection($this->service->createList($list)));
    }

    public function createListMember($id, Request $request)
    {
      $member = $request->toArray();
      return $this->res(new SerialisableCollection($this->service->createListMember($id, $member)));
    }

    public function updateListMember($id, $memberId, Request $request)
    {
      $member = $request->toArray();
      return $this->res(new SerialisableCollection($this->service->updateListMember($id, $memberId, $member)));
    }

}
