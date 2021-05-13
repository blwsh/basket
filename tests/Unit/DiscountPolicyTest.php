<?php namespace blwsh\basket\Tests\Unit;

use PHPUnit\Framework\TestCase;
use blwsh\basket\{Basket, BasketItem, DiscountPolicy, Exceptions\UnableToAddToBasketException, Product};

/**
 * Class DiscountPolicyTest
 *
 * @package Unit
 */
class DiscountPolicyTest extends TestCase
{
    /**
     * @var Basket
     */
    protected Basket $basket;

    /**
     * @var BasketItem
     */
    protected BasketItem $item;

    /**
     * @throws UnableToAddToBasketException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->basket = new Basket;

        $this->basket->add(
            $this->item = (new Product(['name' => 'Test Product', 'price' => 100]))->ignoreStockChecks()->toBasketItem()
        );
    }

    /**
     *
     */
    public function testFixedDiscount()
    {
        // Apply two 25p discounts to the basket item.
        foreach (range(1, 2) as $_) {
            $this->item->applyDiscount(
                new DiscountPolicy(
                    name: '£0.25 off',
                    amount: 25,
                    type: 'fixed',
                )
            );
        }

        $this->assertEquals(50, $this->item->discountedTotal());
        $this->assertEquals(100, $this->item->total());
    }

    /**
     *
     */
    public function testPercentageDiscount()
    {
        // Apply two 25% discounts to the basket item.
        foreach (range(1, 2) as $_) {
            $this->item->applyDiscount(
                new DiscountPolicy(
                    name: '25% off',
                    amount: 25,
                    type: 'percentage',
                )
            );
        }

        $this->assertEquals(50, $this->item->discountedTotal());
        $this->assertEquals(100, $this->item->total());
    }

    /**
     *
     */
    public function testDiscountPolicyRules()
    {
        $this->item->applyDiscount(
            new DiscountPolicy(
                name: '50% off',
                amount: 50,
                type: 'percentage',
                rules: [
                    fn(BasketItem $item) => false // We return false so the discount should not be applied.
                ]
            )
        );

        $this->assertEquals(100, $this->item->discountedTotal());
        $this->assertEquals(100, $this->item->total());

        $this->item->applyDiscount(
            new DiscountPolicy(
                name: '50% off',
                amount: 50,
                type: 'percentage',
                rules: [
                    fn(BasketItem $item) => true // We return true so the discount should be applied.
                ]
            )
        );

        $this->assertEquals(50, $this->item->discountedTotal());
        $this->assertEquals(100, $this->item->total());
    }
}
