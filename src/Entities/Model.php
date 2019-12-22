<?php


namespace Laragen\Entities;


class Model
{

    /**
     * Model name
     * @var string
     */
    private $name;

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
    public function enableSoftDeletes(bool $softDeletes)
    {
        $this->softDeletes = $softDeletes;
    }

    public function disableTimestamps()
    {
        $this->timestamps = false;
    }

}