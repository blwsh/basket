<?php namespace blwsh\basket;

/**
 * Class DiscountPolicy
 *
 * @package blwsh\basket
 */
class DiscountPolicy
{
    /**
     * DiscountPolicy constructor.
     *
     * @param string $name      The name of the discount policy.
     * @param int    $amount    The percentage/fixed amount to discount.
     * @param string $type      percentage|fixed.
     * @param array  $rules     An array of callbacks which are each passed a BasketItem to validate against.
     *                          All of the callbacks must return true if the discount is tobe applied.
     * @param array  $appliesTo An array of product ids the discount can apply to.
     *                          This is not ideal because it doesn't global discounts and categories in to account
     *                          however, this is out of scope for now.
     * @param bool   $distinct
     */
    public function __construct(
        protected string $name = 'New Discount Policy',
        protected int $amount = 0,
        protected string $type = 'fixed',
        protected array $rules = [],
        protected array $appliesTo = [],
        protected bool $distinct = true
    ) {}

    /**
     * @param BasketItem $item
     *
     * @return bool
     */
    public function canApplyDiscount(BasketItem $item): bool
    {
        // If any of the callbacks return false we stop and return false.
        foreach ($this->rules as $rule) {
            if (!$rule($item, $this)) return false;
        }

        // Otherwise, all conditions were met so we can return true.
        return true;
    }

    /**
     * @param BasketItem $item
     * @param bool       $bypassCheck
     *
     * @return int
     */
    public function getDeduction(BasketItem $item, $bypassCheck = false): int
    {
        if ($bypassCheck || $this->canApplyDiscount($item)) {
            return $this->type === 'percentage'
                ?  $item->total() * ($this->amount / 100)
                :  $this->amount;
        } else {
            return 0;
        }
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function amount(): int
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return $this->rules;
    }

    /**
     * @return array
     */
    public function appliesTo(): array
    {
        return $this->appliesTo;
    }

    /**
     * @return bool
     */
    public function isDistinct(): bool
    {
        return $this->distinct;
    }
}
