<?php


namespace Laragen\Factories;


use Illuminate\Support\Str;
use Laragen\Contracts\Factory;
use Laragen\Entities\Model;

class ModelFactory implements Factory
{

    public static function make(string $name, array $data): Model
    {

        $model = new Model(Str::studly($name));

        foreach($data as $name =>  $field){
            if (method_exists($model, $method = 'set'.Str::camel($name))){
                $model->$method($field);
            }
        }

        if(isset($data['timestamps']) && ! $data['timestamps']) {
            $model->disableTimestamps();
        }

        if(isset($data['softDeletes']) && $data['softDeletes']) {
            $model->enableSoftDeletes();
        }

        return $model;

    }

}