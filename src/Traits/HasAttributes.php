<?php namespace blwsh\basket\Traits;

/**
 * Trait HasAttributes
 *
 * @package blwsh\basket\Traits
 */
trait HasAttributes
{
    /**
     * @var array The store for attributes.
     */
    protected array $attributes = [];

    /**
     * @var array Attributes which may not be set directly.
     */
    protected array $guarded = [];

    /**
     * HasAttributes constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name): mixed
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (!in_array($name, $this->guarded)) {
            $this->attributes[$name] = $value;
        } else {
            // Could maybe throw an exception here informing developers they're trying to set
            // the value of a guarded property or just ignore it.
        }
    }

    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }
}
