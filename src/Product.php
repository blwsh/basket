<?php namespace blwsh\basket;

use blwsh\basket\Utils\UUID;
use blwsh\basket\Traits\{HasStock, HasAttributes};
use blwsh\basket\Contracts\{Stockable, Purchasable};

/**
 * Class Product
 *
 * @property string id
 * @property string name
 * @property int    price
 *
 * @package blwsh\basket
 */
class Product implements Purchasable, Stockable
{
    use HasAttributes, HasStock;

    /**
     * @var array|int[]
     */
    protected array $defaultAttributes = ['price' => 0];

    /**
     * Product constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {

        $this->setAttributes(array_merge($this->defaultAttributes, $attributes));
        $this->id = UUID::generate();
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return BasketItem
     */
    public function toBasketItem(): BasketItem
    {
        return new BasketItem($this);
    }
}
