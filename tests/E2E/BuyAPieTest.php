<?php namespace blwsh\basket\Tests\E2E;

use PHPUnit\Framework\TestCase;
use blwsh\basket\{Basket, Exceptions\UnableToAddToBasketException, Product, StockRecord};

/**
 * Class BuyAPieTest
 *
 * @package blwsh\basket\Tests\E2E
 */
class BuyAPieTest extends TestCase
{
    /**
     * @var Basket
     */
    protected Basket $basket;

    /**
     * @var Product
     */
    protected Product $product;

    /**
     * @var Product
     */
    protected Product $aboutToExpireProduct;

    /**
     * @var Product
     */
    protected Product $expiredProduct;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->basket = new Basket;

        $this->product = new Product([
            'name' => 'Pie',
            'price' => 320,
            'stock' => [
                new StockRecord(100, timestamp('now'), timestamp('+1 day'))
            ]
        ]);

        $this->aboutToExpireProduct = new Product([
            'name' => 'Pie',
            'price' => 320,
            'stock' => [
                new StockRecord(100, timestamp('-1 day'), timestamp('+1 minute'))
            ]
        ]);

        $this->expiredProduct = new Product([
            'name' => 'Pie',
            'price' => 320,
            'stock' => [
                new StockRecord(100, timestamp('-2 day ago'), timestamp('-1 day'))
            ]
        ]);
    }

    /**
     * @throws UnableToAddToBasketException
     */
    public function testCanBuyBeforeExpiryDate()
    {
        // Add the item to the basket and make sure it's in there.
        $this->basket->add($item = $this->product->toBasketItem());
        $this->assertTrue($this->basket->hasBasketItem($item));
    }

    /**
     * @throws UnableToAddToBasketException
     */
    public function testCanNotBuyWhenPastExpiryDate()
    {
        // When we try to add an item that has expired, the add method should throw UnableToAddToBasketException.
        $this->expectException(UnableToAddToBasketException::class);
        $this->basket->add($item = $this->expiredProduct->toBasketItem());
    }

    /**
     * @throws UnableToAddToBasketException
     */
    public function testDiscountAppliedOnExpiryDate()
    {
        $this->basket->add($item = $this->aboutToExpireProduct->toBasketItem());
        $this->assertTrue($this->basket->hasBasketItem($item));
        $this->assertEquals(160, $item->discountedTotal());
    }
}
