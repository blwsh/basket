<?php namespace blwsh\basket;

use blwsh\basket\Contracts\Purchasable;
use blwsh\basket\Traits\HasAttributes;
use JsonSerializable;

/**
 * Class BasketItem
 *
 * @package blwsh\basket
 */
class BasketItem implements JsonSerializable
{
    use HasAttributes;

    /**
     * @var int
     */
    protected int $total = 0;

    /**
     * @var int
     */
    protected int $discountedTotal = 0;

    /**
     * @var array
     *
     * @note Probably a good idea to use a collection here for discount
     */
    protected array $discountPolicies = [];

    /**
     * BasketItem constructor.
     *
     * @param Purchasable $purchasable
     * @param int         $quantity
     * @param Basket      $basket
     */
    public function __construct(
        protected Basket $basket,
        protected Purchasable $purchasable,
        protected int $quantity = 1,
    ) {
        $this->total = $this->purchasable->getPrice();

        try {
            $this->setQuantity($this->quantity ?? 1);
        } catch (InvalidQuantityException) {
            // We know the quantity can never be zero so we can ignore this exception.
        }
    }

    /**
     * @return int
     */
    public function quantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return Purchasable
     */
    public function purchsable(): Purchasable
    {
        return $this->purchasable;
    }

    /**
     * @param int $quantity
     *
     * @throws InvalidQuantityException
     */
    public function setQuantity(int $quantity)
    {
        // We could just call removeFromBasket but this could lead to bugs so I've opted to show an error instead.
        if ($quantity < 1) throw new InvalidQuantityException;

        $this->quantity = $quantity;
        $this->total = $quantity * $this->purchasable->getPrice();
    }

    /**
     *
     */
    public function removeFromBasket(): bool
    {
        $this->basket->remove($this);
        return true;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'purchsable' => $this->purchasable,
            'quantity' => $this->quantity,
            'total' => $this->total,
            'discountedTotal' => $this->discountedTotal,
        ];
    }
}
