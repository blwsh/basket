<?php namespace blwsh\basket;

/**
 * Class Basket
 *
 * @package blwsh\basket
 */
class Basket
{
    /**
     * @var BasketItem[]
     */
    protected $items = [];

    /**
     * @return BasketItem[]
     */
    public function items()
    {
        return $this->items;
    }

    public function addBasketItem(BasketItem $item)
    {
        $this->items[] = $item;
    }

    public function removeBasketItem(BasketItem $item)
    {
        echo array_search($item->id, array_column($this->items(), 'id')); // prints 0 (!== false)
    }
}
