<?php

namespace Laragen\Builders;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Laragen\Contracts\FileBuilder;
use Laragen\Entities\Model;

class ModelFileBuilder implements FileBuilder
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var string
     */
    protected $body;

    /**
     * ModelBuilder constructor.
     *
     * @param Model $model
     * @param $files
     */
    public function __construct(Model $model)
    {

        $this->model = $model;

        $this->files = new Filesystem();
    }

    /**
     * build  the model class file for output
     *
     */
    public function execute()
    {
        $modelfile = str_replace('//...', $this->buildBody(), $this->buildClass());

        $this->files->put(dirname(__DIR__)."/{$this->model->name()}.php", $modelfile);
    }

    protected function buildClass()
    {
        $model_class_body = $this->files->get(dirname(__DIR__).'/stubs/model/class.stub');

        $model_class_body = str_replace(['ClassPlaceHolder', 'NamespacePlaceHolder'],
            [$this->model->name(), $this->model->namespace()], $model_class_body);

        if ($this->model->usesSoftDeletes()) {
            $model_class_body = $this->addSoftDeletes($model_class_body);
        }

        return $model_class_body;
    }

    protected function buildBody()
    {
        $this->addFillables()
            ->addDates()
            ->addCasts()
            ->addRouteKeyName();

        return $this->body;
        // todo relations, table primarykey

    }

    private function addFillables()
    {
        if (! empty($fillables = $this->model->fillables())) {
            $stub = $this->files->get(dirname(__DIR__).'/stubs/model/fillable.stub');
            $parts = '';
            foreach ($fillables as $part) {
                $parts .= "'{$part}', ";
            }
            $this->body .= PHP_EOL.str_replace('//...', $parts, $stub);
        }

        return $this;
    }

    private function addDates()
    {
        if (isset($this->model->casts()['datetime'])) {

            $dates = $this->model->casts()['datetime'];
            $stub = $this->files->get(dirname(__DIR__).'/stubs/model/dates.stub');
            $dates = preg_split('/\s+/', trim($dates));

            $parts = '';
            foreach ($dates as $part) {
                $parts .= "'{$part}', ";
            }
            $this->body .= PHP_EOL.str_replace('//...', $parts, $stub);
        }

        return $this;
    }

    private function addCasts()
    {

        if (! empty($this->model->casts())) {

            $casts = Arr::except($this->model->casts(), ['datetime']);

            $cast_array = '';

            foreach ($casts as $cast_type => $proerties) {
                $attrs = preg_split('/\s+/', trim($proerties));
                foreach ($attrs as $attr) {
                    $cast_array .= "\t\t'{$attr}' => '{$cast_type}',".PHP_EOL;
                }
            }
            $stub = $this->files->get(dirname(__DIR__).'/stubs/model/casts.stub');

            $this->body .= PHP_EOL.str_replace('//...', $cast_array, $stub);
        }

        return $this;
    }

    /**
     * Add softDeletes Trait
     *
     * @param string $model_class_stub
     * @return string|string[]
     */
    private function addSoftDeletes(string $model_class_stub)
    {
        $model_class_stub = str_replace(
            'use Illuminate\\Database\\Eloquent\\Model;',
            'use Illuminate\\Database\\Eloquent\\Model;'.PHP_EOL.'use Illuminate\\Database\\Eloquent\\SoftDeletes;',
            $model_class_stub);

        $model_class_stub = preg_replace('/^\{$/m', '{'.PHP_EOL.'    use SoftDeletes;', $model_class_stub);

        return $model_class_stub;
    }

    private function addRouteKeyName()
    {
        if ($this->model->routeKeyName() != 'id') {

            $stub = $this->files->get(dirname(__DIR__).'/stubs/model/method.stub');

            $stub = str_replace(
                ['PlaceHolderName', 'null'],
                ['getRouteKeyName', "'{$this->model->routeKeyName()}'"],
                $stub);

            $this->body .= PHP_EOL.$stub;
        }

        return $this;
    }
}