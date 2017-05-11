<?php namespace App\Services;

use \Exception;

/**
* Warning suppressed because having a single parent class makes
* sense in this context.
*
* @SuppressWarnings(PHPMD.NumberOfChildren)
*/
abstract class BaseService
{
    public $modelName;
    public $entityName;
    public $entityNamePlural;
    public $currentUser;
    public $validator;
    public $context;

    /**
     * Setup entity.
     *
     * @return void
     */
    public function __construct()
    {
        $name = str_replace('Service', '', class_basename(static::class));
        $this->entityName = strtolower($name);
        $this->entityNamePlural = strtolower(str_plural($name));
        $this->modelName = "App\\Models\\{$name}";
        $this->context = app()->make('AppContext');
    }

    /**
     * Create a new model instance.
     *
     * @return mixed
     */
    public function model(array $data = [])
    {
        return new $this->modelName($data);
    }

    /**
     * Get a listing of the resource.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->model()->all();
    }

    /**
     * Get a paginated listing of the resource.
     *
     * @param  int  $count
     * @return mixed
     */
    public function paginate(int $count)
    {
        return $this->model()->paginate($count);
    }

    /**
     * Create a new model instance.
     *
     * @return mixed
     */
    public function create(array $data = [])
    {
        $this->validate($data, null);
        return $this->model()->create($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return mixed
     */
    public function save($id, array $data)
    {
        $mod = $this->findOrFail($id);
        $this->validate($data, $mod);
        $mod->update($data);
        return $mod;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return mixed
     */
    public function update($resource, array $data)
    {
        $this->validate($data, $resource);
        return $resource->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy(int $id)
    {
        $mod = $this->model()->findOrFail($id);
        $mod->destroy($id);

        return $this->modelName::withTrashed()
            ->where('id', $id)
            ->first();
    }

    /**
     * Replicate the specified resource.
     *
     * @param  int  $id
     * @return mixed
     */
    public function replicate(int $id)
    {
        return $this->model()->find($id)->replicate();
    }

    /**
     * Find a resource by id, or throw an exception if no result is found.
     *
     * @param  int  $id
     * @return mixed
     */
    public function findOrFail(int $id)
    {
        return $this->model()->findOrFail($id);
    }

    /**
     * Find a resource by id.
     *
     * @param  int  $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model()->find($id);
    }

    /**
     * Get the first resource.
     *
     * @return mixed
     */
    public function first()
    {
        return $this->model()->first();
    }

    /**
     * See if a model exists.
     *
     * @return boolean
     */
    public function exists()
    {
        return $this->model()->exists();
    }

    /**
     * BaseService constructor.
     * @param Request $request
     * Get a count of the resouce.
     *
     * @return int
     */
    public function count()
    {
        return $this->model()->count();
    }

    /**
     * BaseService constructor.
     * @param Request $request
     * Get a count of the resouce.
     *
     * @return int
     */
    public function searchCols($query, $text)
    {
        $cols = array_keys($this->model()->toSearchableArray());
        $query->where(function ($query) use ($cols, $text) {
            foreach ($cols as $col) {
                $query->orWhere($col, 'ilike', "%{$text}%");
            }
        });
        return $query;
    }

    /**
     * BaseService constructor.
     * @param Request $request
     * Get a count of the resouce.
     *
     * @return int
     */
    public function orderResults($query, $orderArray)
    {
        $query->orderBy($orderArray['column'], $orderArray['dir']);

        return $query;
    }

    public function validate($data, $model)
    {
        $name = str_replace('Service', '', class_basename(static::class));
        $validatorName = "\\App\\CustomValidator\\{$name}Validator";

        if (!class_exists($validatorName)) {
            \Log::warn("No validator found for the model '{$name}' at '{$validatorName}'");
            return;
        }
        // CODESMELL: the validator should not rely on the request, when we scarp v1 api
        // we can remove passing the request in
        $validator = new $validatorName(new \Illuminate\Http\Request(), $model);
        $validator->validate($data);
    }
}
