<?php namespace blwsh\basket;

class DiscountPolicy
{
    /**
     * DiscountPolicy constructor.
     *
     * @param int    $amount    The percentage/fixed amount to discount.
     * @param string $type      percentage|fixed.
     * @param array $rules      An array of callbacks which are each passed a BasketItem to validate against.
     *                          All of the callbacks must return true if the discount is tobe applied.
     * @param array  $appliesTo An array of product ids the discount can apply to.
     *                          This is not ideal because it doesn't global discounts and categories in to account
     *                          however, this is out of scope for now.
     */
    public function __construct(
        protected int $amount,
        protected string $type,
        protected array $rules,
        protected array $appliesTo = [],
    ) {}
}
