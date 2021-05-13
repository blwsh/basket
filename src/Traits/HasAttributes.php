<?php namespace blwsh\basket\Traits;

/**
 * Trait HasAttributes
 *
 * Allows arbitrary properties for any class which uses the trait.
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
        $this->setAttributes($attributes);
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $key => $value) $this->$key = $value;
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

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
