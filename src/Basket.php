<?php namespace blwsh\basket;

use blwsh\basket\DiscountPolicies\DiscountAboutToExpireItems;
use JsonSerializable;
use blwsh\basket\Exceptions\UnableToAddToBasketException;

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
     * @param BasketItem $item
     *
     * @return bool
     */
    public function canAddToBasket(BasketItem $item): bool
    {
        return $item->purchsable()->hasStock(timestamp('now'))
            && !$item->purchsable()->isStockExpired(timestamp('now'));
    }

    /**
     * Adds new items to the basket items array and increments existing basket item quantities.
     *
     * @param BasketItem $item
     * @param int        $quantity
     *
     * @return $this
     * @throws UnableToAddToBasketException
     */
    public function add(BasketItem $item, $quantity = 1): self
    {
        if (!$this->canAddToBasket($item)) {
            throw new UnableToAddToBasketException;
        }

        // This should really come from a global store which applies global discount policies based on conditions
        // but for now we'll do it here.
        if ($item->purchsable()->hasExpiringStock(timestamp('now'))) {
            $item->applyDiscount(new DiscountAboutToExpireItems);
        }

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
