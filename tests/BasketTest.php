<?php namespace blwsh\basket\Tests;

use blwsh\basket\Basket;
use blwsh\basket\BasketItem;
use blwsh\basket\Product;
use PHPUnit\Framework\TestCase;

/**
 * Class BasketTest
 *
 * @package blwsh\basket\Tests
 */
class BasketTest extends TestCase
{
    /**
     * @var Basket
     */
    protected Basket $basket;
    private Product $testProduct;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basket = new Basket;
        $this->testProduct = new Product(['name' => 'Test Product One', 'price' => 100]);
    }

    /**
     *
     */
    public function testAddPurchasableItemToBasket()
    {
        $this->assertCount(0, $this->basket->items());

        $this->basket->add($this->testProduct);

        $this->assertCount(1, $this->basket->items());
        $item = $this->basket->items()[0];
        $this->assertEquals($this->testProduct, $item->purchsable());

        // Adding the same product to the basket again should increment the basket item
        // quantity.
        $this->basket->add($this->testProduct);

        $this->assertCount(1, $this->basket->items());
        $this->assertEquals(2, $this->basket->items()[0]->quantity());
    }

    /**
     *§
     */
    public function testRemovePurchasableItemFromBasket()
    {
        $this->assertTrue(true);
    }
}
