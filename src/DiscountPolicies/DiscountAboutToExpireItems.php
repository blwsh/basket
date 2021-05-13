<?php namespace blwsh\basket\DiscountPolicies;

use blwsh\basket\BasketItem;
use blwsh\basket\DiscountPolicy;

/**
 * Class DiscountAboutToExpireItems
 *
 * @package blwsh\basket\DiscountPolicies
 */
class DiscountAboutToExpireItems extends DiscountPolicy
{
    /**
     * @inheritDoc
     */
    public function __construct(
        protected string $name = '50% off. Must go today!',
        protected int $amount = 50,
        protected string $type = 'percentage',
        protected array $rules = [],
        protected array $appliesTo = [],
    ) {
        $this->rules = [fn(BasketItem $item) => $item->purchsable()->hasExpiringStock(timestamp('now'))];
    }
}
