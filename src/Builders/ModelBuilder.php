<?php


namespace Laragen\Builders;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Laragen\Contracts\Builder;
use Laragen\Entities\Model;

class ModelBuilder
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
    protected $body = '';

    /**
     * ModelBuilder constructor.
     *
     * @param  Model  $model
     * @param $files
     */
    public function __construct(Model $model, $files)
    {

        $this->model = $model;

        $this->files = $files;
    }

    /**
     * build  the model class file for output
     *
     */
    public function build()
    {
            $modelfile = str_replace('//...', $this->buildBody(), $this->buildClass());

            $this->files->put(dirname(__DIR__)."/{$this->model->name()}.php", $modelfile);

    }


    private function buildClass()
    {
        $model_class_body = $this->files->get(dirname(__DIR__).'/stubs/model/class.stub');

        $model_class_body = str_replace('ClassPlaceHolder', $this->model->name(), $model_class_body);
        $model_class_body = str_replace('NamespacePlaceHolder', $this->model->namespace(), $model_class_body);

        return $model_class_body;
    }


    private function buildBody()
    {
        $this->buildFillables()
             ->buildDates()
            ->buildCasts();

        return $this->body;

        // todo relations, soft delete table primarykey route key

    }

    private function buildFillables()
    {
        if (!empty($fillables = $this->model->fillables())) {
            $stub  = $this->files->get(dirname(__DIR__).'/stubs/model/fillable.stub');
            $parts = '';
            foreach ($fillables as $part) {
                $parts .= "'{$part}', ";
            }
            $this->body .= PHP_EOL.str_replace('//...', $parts, $stub);
        }

        return $this;
    }

    private function buildDates()
    {


        if (isset($this->model->casts()['datetime'])) {

            $dates = $this->model->casts()['datetime'];
            $stub  = $this->files->get(dirname(__DIR__).'/stubs/model/dates.stub');
            $dates = explode(' ', $dates);
            $parts= '';
            foreach ($dates as $part) {
                $parts .= "'{$part}', ";
            }
            $this->body .= PHP_EOL. str_replace('//...', $parts, $stub);
        }

        return $this;

    }


    private function buildCasts()
    {

        if (! empty($this->model->casts())){

            $casts = Arr::except($this->model->casts(), ['datetime']);
            $cast_arr = '';
            foreach ($casts as $attr => $cast_type) {
                $cast_arr .= "'{$attr}' => '{$cast_type}',\n";
            }
            $stub  = $this->files->get(dirname(__DIR__).'/stubs/model/casts.stub');

            $this->body .= PHP_EOL.str_replace('//...',  $cast_arr, $stub);
        }

        return $this;

    }
}