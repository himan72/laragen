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

        if(isset($data['attributes'])) {
        $model->setAttributes($data['attributes']);
        }

        if(isset($data['casts'])) {
            $model->setCasts($data['casts']);
        }

        if(isset($data['relations'])) {
            $model->setRelations($data['relations']);
        }

        if(isset($data['timestamps']) && ! $data['timestamps']) {
            $model->disableTimestamps();
        }

        if(isset($data['sofDeletes']) && $data['sofDeletes']) {
            $model->enableSoftDeletes();
        }

        return $model;

    }

}