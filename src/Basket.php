<?php namespace blwsh\basket;

use JsonSerializable;

/**
 * Class Basket
 *
 * @package blwsh\basket
 */
class Basket implements JsonSerializable
{
    /**
     * @var BasketItem[]
     */
    protected array $items = [];

    /**
     * @return BasketItem[]
     */
    public function items(): array
    {
        return $this->items;
    }

    /**
     * @param BasketItem $item
     *
     * @return bool
     */
    public function hasBasketItem(BasketItem $item): bool
    {
        return isset($this->items[$item->id()]);
    }

    /**
     * Adds new items to the basket items array and increments existing basket item quantities.
     *
     * @param BasketItem $item
     * @param int        $quantity
     *
     * @return $this
     * @throws InvalidQuantityException
     */
    public function add(BasketItem $item, $quantity = 1): self
    {
        $item->setBasket($this);

        if (!$this->hasBasketItem($item)) {
            $this->items[$item->id()] = $item;
        }

        $item->setQuantity($item->quantity() + $quantity);

        return $this;
    }

    /**
     * Decrements the basket item quantity. If the new basket quantity is zero or less, the basket item is removed from
     * the basket.
     *
     * @param BasketItem $item
     * @param int        $quantity
     *
     * @return $this
     * @throws InvalidQuantityException
     */
    public function remove(BasketItem $item, $quantity = 1): self
    {
        if ($item->quantity() - $quantity > 0) {
            $item->setQuantity($item->quantity() - $quantity);
        } else {
            unset($this->items[$item->id()]);
        }

        return $this;
    }

    /**
     * @return BasketItem[][]
     */
    public function jsonSerialize(): array
    {
        return [
            'items' => $this->items()
        ];
    }
}
