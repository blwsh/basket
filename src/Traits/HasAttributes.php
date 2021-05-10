<?php namespace blwsh\basket\Traits;

trait HasAttributes
{
    protected array $attributes = [];

    public function __get($name)
    {
        return $this?->attributes[$name];
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }
}
