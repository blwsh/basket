<?php namespace blwsh\basket\Tests\E2E;

use blwsh\basket\Basket;
use blwsh\basket\BasketItem;
use blwsh\basket\DiscountPolicy;
use blwsh\basket\InvalidQuantityException;
use blwsh\basket\Product;
use DateTime;
use PHPUnit\Framework\TestCase;

class BuyAPieTest extends TestCase
{
    /**
     * @var Product
     */
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = $pie = new Product([
            'name' => 'Pie',
            'price' => 320,
            'stock' => [
                ['stock' => 100, 'expires' => (new DateTime('tomorrow'))->format('Y-m-d H:i:s')]
            ]
        ]);
    }

    /**
     * @throws InvalidQuantityException
     */
    public function testCanBuyBeforeExpiryDate()
    {
        $basket = new Basket;

        $basket->add($item = $this->product->toBasketItem());

        // Check the item is in basket
        $this->assertTrue($basket->hasBasketItem($item));
    }

    public function testCanNotBuyWhenPastExpiryDate()
    {

    }

    public function testDiscountAppliedOnExpiryDate()
    {
        $discount = new DiscountPolicy(
            amount: 50,
            type: 'percentage',
            rules: [
                fn(BasketItem $item) => $item->purchsable()->hasExpiringStock((new DateTime('tomorrow'))->format('Y-m-d H:i:s'))
            ]
        );
    }
}
