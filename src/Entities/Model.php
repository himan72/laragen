<?php


namespace Laragen\Entities;


use Illuminate\Support\Str;

class Model
{

    /**
     * Model name
     * @var string
     */
    private $name;

    /**
     * Model name
     * @var string
     */
    private $namespace = 'App';

    /**
     * model attributes
     * @var array
     */
    private $attributes = [];

    /**
     * model attributes to be casted
     * @var array
     */
    private $casts = [];

    /**
     * @var array
     */
    private $relations = [];

    /**
     * @var bool
     */
    private $timestamps = true;

    /**
     * @var bool
     */
    private $softDeletes = false;

    /**
     * @var string
     */
    private $tableName;

    /**
     * the model route key name
     *
     * @var string
     */
    private $route_key_name= 'id';


    /**
     * Model constructor.
     *
     * @param  string  $name
     */
    public function __construct(string $name)
    {

        $this->name = $name;

    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param  array  $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function casts(): array
    {
        return $this->casts;
    }

    /**
     * Model eloquent relations
     *
     * @param  array  $casts
     */
    public function setCasts(array $casts)
    {
        $this->casts = $casts;

    }

    /**
     * Model eloquent relations
     * @return array
     */
    public function relations(): array
    {
        return $this->relations;
    }

    /**
     * setter for Model eloquent relations
     *
     * @param  array  $relations
     *
     * @return array
     */
    public function setRelations(array $relations): array
    {
        $this->relations = $relations;
    }

    /**
     * @return string
     */
    public function usesTimestamps(): bool
    {
        return $this->timestamps;
    }

    /**
     * @return bool
     */
    public function usesSoftDeletes(): bool
    {
        return $this->softDeletes;
    }

    /**
     * @param  bool  $softDeletes
     */
    public function enableSoftDeletes()
    {
        $this->softDeletes = true;
    }

    public function disableTimestamps()
    {
        $this->timestamps = false;
    }

    public function fillables()
    {
        return array_keys($this->attributes());

    }

    /**
     * @param  string  $namespace
     *
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;

    }

    /**
     * @return string
     */
    public function namespace()
    {
        return $this->namespace ;

    }

    /**
     * The model's table name
     * @return string
     */
    public function tableName() : string
    {
        return Str::snake(Str::pluralStudly($this->name));

    }

    /**
     * @return string
     */
    public function routeKeyName(): string
    {
        return $this->route_key_name;
    }

    /**
     * @param string $route_key_name
     * @return string
     */
    public function setRouteKeyName(string $route_key_name)
    {
        $this->route_key_name = $route_key_name;
    }
}