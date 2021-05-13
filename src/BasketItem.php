<?php namespace blwsh\basket;

use JsonSerializable;
use blwsh\basket\{Contracts\Purchasable,
    Contracts\Stockable,
    Exceptions\InvalidQuantityException,
    Traits\HasAttributes,
    Utils\UUID};

/**
 * Class BasketItem
 *
 * @package blwsh\basket
 */
class BasketItem implements JsonSerializable
{
    use HasAttributes;

    /**
     * @var string
     */
    protected string $id;
    /**
     * @var int
     */
    protected int $total = 0;

    /**
     * @var int
     */
    protected int $discountedTotal = 0;

    /**
     * @var DiscountPolicy[]
     *
     * @note Probably a good idea to use a collection here for discount
     */
    protected array $discountPolicies = [];

    /**
     * @var Basket|null
     */
    protected ?Basket $basket = null;

    /**
     * BasketItem constructor.
     *
     * @param Purchasable|Stockable $purchasable
     * @param int                   $quantity
     *
     * @throws InvalidQuantityException
     */
    public function __construct(
        protected Purchasable|Stockable $purchasable,
        protected int $quantity = 0,
    )
    {
        $this->id = UUID::generate();
        $this->setQuantity($this->quantity);
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return Basket|null
     */
    public function basket(): Basket|null
    {
        return $this->basket;
    }

    /**
     * @param Basket $basket
     */
    public function setBasket(Basket $basket)
    {
        $this->basket = $basket;
    }

    /**
     * @return Purchasable|Stockable
     */
    public function purchsable(): Purchasable|Stockable
    {
        return $this->purchasable;
    }

    /**
     * @return int
     */
    public function total(): int
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function discountedTotal(): int
    {
        return $this->discountedTotal;
    }

    /**
     * @return int
     */
    public function quantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @throws InvalidQuantityException
     */
    public function setQuantity(int $quantity)
    {
        // We could just call removeFromBasket but this could lead to bugs so I've opted to show an error instead.
        if ($quantity < 0) throw new InvalidQuantityException;

        $this->quantity = $quantity;

        $this->calculateTotal();
        $this->calculateDiscountTotal();
    }

    /**
     *
     */
    public function calculateTotal()
    {
        $this->total = $this->discountedTotal = $this->quantity * $this->purchasable->getPrice();
    }

    /**
     * Iterates over all discount policies for the basket items and applies them to the total to create a discountedTotal.
     */
    public function calculateDiscountTotal()
    {
        $this->discountedTotal = $this->total;

        foreach ($this->discountPolicies as $policy) {
            $this->discountedTotal = $this->discountedTotal - $policy->getDeduction($this);
        }
    }

    /**
     * @return DiscountPolicy[]
     */
    public function discountPolicies(): array
    {
        return $this->discountPolicies;
    }

    /**
     * @param DiscountPolicy $discountPolicy
     *
     * @return BasketItem
     */
    public function applyDiscount(DiscountPolicy $discountPolicy): self
    {
        $this->discountPolicies[] = $discountPolicy;

        // We recalculate all basket items discount prices if the discount item is is associated with a basket
        // otherwise we only need to calculate the discount total for this basket item.
        if ($this->basket) {
            foreach ($this->basket->items() as $item) {
                $item->calculateDiscountTotal();
            }
        } else {
            $this->calculateDiscountTotal();
        }

        return $this;
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
