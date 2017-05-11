<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BaseService;
use App\Http\Controllers\Controller;

/**
* Warning suppressed because having a single parent class makes
* sense in this context.
*
* @SuppressWarnings(PHPMD.NumberOfChildren)
*/
abstract class BaseController extends Controller
{
    use \App\Traits\KeyPath;

    protected $service = null;
    protected $with = [];
    protected $search = false;
    protected $perPage = 10;
    protected $context = null;
    protected $order = [];
    public function __construct(Request $request, BaseService $service)
    {
        $this->service = $service;
        $this->with = $request->query('with') ?? [];
        $this->search = $request->query('search') ? $request->query('search')['value'] : false;
        $this->perPage = $request->query('limit') ?? 10;
        $this->order = $request->query('order') ?? [];
        $this->context = app()->make('AppContext');
    }
    protected function select($initialQuery = null)
    {
        $query = (!$initialQuery) ?
            $this->service->modelName::inContext($this->context)
            :
            $initialQuery->inContext($this->context);

        if ($this->search) {
            $this->service->searchCols($query, $this->search);
        }

        if ($this->order) {
            $orderArray = collect($this->order)->flatMap(function ($order) {
                return ["column" => $order['column'], 'dir' => $order['dir']];
            })->all();

            $this->service->orderResults($query, $orderArray);
        }
        return $query->with($this->with);
    }
    public function show($id)
    {
        return $this->res($this->select()->findOrFail($id));
    }
    public function index()
    {
        return $this->res($this->select()->paginate($this->perPage));
    }
    public function create(Request $request)
    {
        return $this->res($this->service->create($this->payload($request)));
    }
    public function update(Request $request, $id)
    {
        return $this->res($this->service->save($id, $this->payload($request)));
    }
    public function destroy(Request $request, $id)
    {
        return $this->res($this->service->destroy($id, $this->payload($request)));
    }
    protected function res($data)
    {
        return response($data->serialise($this->context->getResponseFormatter()));
    }
    protected function payload($request)
    {
        return $this->context->getRequestFormatter()->unwrapResult($request->toArray());
    }
}
