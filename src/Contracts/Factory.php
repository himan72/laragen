<?php


namespace Laragen\Contracts;


interface Factory
{
    public static function make(string $name, array $data);

}