<?php namespace blwsh\basket;

use blwsh\basket\Contracts\Purchasable;
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
     * @return bool
     */
    public function hasBasketItem(): bool
    {
        return false;
    }

    /**
     * @param Purchasable $item
     *
     * @return $this
     */
    public function add(Purchasable $item): self
    {
        if ($this->hasBasketItem()) {

        } else {
            $this->items[] = new BasketItem(
                $this, $item, 1
            );
        }

        return $this;
    }

    /**
     * @param BasketItem $item
     *
     * @return $this
     */
    public function remove(BasketItem $item): self
    {
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'items' => $this->items()
        ];
    }
}
