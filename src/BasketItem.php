<?php namespace blwsh\basket;

use blwsh\basket\Contracts\Purchasable;
use blwsh\basket\Contracts\Stockable;
use blwsh\basket\Traits\HasAttributes;
use blwsh\basket\Utils\UUID;
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
     * @var array
     *
     * @note Probably a good idea to use a collection here for discount
     */
    protected array $discountPolicies = [];
    /**
     * @var Basket
     */
    protected Basket $basket;

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
        $this->total = $this->purchasable->getPrice();
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
     * @return Basket
     */
    public function basket(): Basket
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
        $this->total = $quantity * $this->purchasable->getPrice();
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
