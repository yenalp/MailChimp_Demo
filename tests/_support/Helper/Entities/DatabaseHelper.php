<?php
namespace Helper\Entities;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
// See here for details: http://codeception.com/docs/10-WebServices#REST

class DatabaseHelper extends \Codeception\Module
{
    public function existsInDatabase($model, $conditions = [])
    {
        if (empty($conditions)) {
            return false;
        }

        $modelName = "\App\Models\\". $model;
        return $modelName::where($conditions)->exists();
    }

    public function insertInToDatabase($model, $fieldValues = [])
    {
        if (empty($fieldValues)) {
            return false;
        }

        $modelName = "\App\Models\\". $model;
        return $modelName::create($fieldValues);
    }

    public function countRowsInTheDatabase($model, $conditions = [])
    {
        $modelName = "\App\Models\\". $model;
        if (empty($conditions)) {
            return $modelName::count();
        }
        return $modelName::where($conditions)->count();
    }


    public function countAllTheDatabase($model)
    {
        $modelName = "\App\Models\\". $model;
        return $modelName::count();
    }

    public function getIdByCondition($model, $conditions = [])
    {
        $modelName = "\App\Models\\". $model;
        if (empty($conditions)) {
            return $modelName::first()->id;
        }
        return $modelName::where($conditions)->first()->id;
    }

    public function getRowByCondition($model, $conditions = [])
    {
        $modelName = "\App\Models\\". $model;
        if (empty($conditions)) {
            return $modelName::first();
        }
        return $modelName::where($conditions)->first();
    }

    public function returnAll($model)
    {
        $modelName = "\App\Models\\". $model;
        return $modelName::get();
    }
}
