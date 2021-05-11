<?php namespace blwsh\basket;

use Exception;
use blwsh\basket\Contracts\Purchasable;
use blwsh\basket\Traits\HasAttributes;

/**
 * Class Product
 *
 * @property string id
 * @property string name
 * @property int    price
 *
 * @package blwsh\basket
 */
class Product implements Purchasable
{
    use HasAttributes;

    /**
     * Product constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = ['price' => 0])
    {
        $this->attributes = $attributes;

        try {
            // Set id as random 20 char string
            $this->id = bin2hex(random_bytes(10));
        } catch (Exception) {
            // We're just going to ignore this for now. In reality, random_bytes will never throw an exception.
            // Maybe could add logging here or a retry loop with a circuit break in the future.
        }
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }
}
