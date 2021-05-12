<?php namespace blwsh\basket\Tests\Unit;

use Exception;
use PHPUnit\Framework\TestCase;
use blwsh\basket\{Basket, BasketItem, InvalidQuantityException, Product};

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

    /**
     * @var Product
     */
    private Product $testProduct;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->basket = new Basket;
        $this->testProduct = new Product(['name' => 'Test Product One', 'price' => 100]);
    }

    /**
     * A simple utility function that adds or removes n items to/from a basket and returns the updated basket.
     *
     * @param Basket     $basket
     * @param BasketItem $item
     * @param            $count
     * @param            $action
     *
     * @return Basket
     * @throws Exception
     */
    public function modifyBasketItemCount(Basket $basket, BasketItem $item, int $count, string $action): Basket
    {
        if (!in_array($action = strtolower($action), ['add', 'remove'])) {
            throw new Exception('Action must be add or remove');
        }

        // Add the same item to the basket ten times
        foreach (range(1, $count) as $_) {
            $basket->$action($item);
        }

        return $basket;
    }

    /**
     * @throws InvalidQuantityException
     */
    public function testBasketItemAssignedToBasket()
    {
        $this->basket->add($item = $this->testProduct->toBasketItem());
        $this->assertInstanceOf(Basket::class, $this->basket);
        $this->assertEquals($this->basket, $item->basket());
    }

    /**
     * @throws InvalidQuantityException
     */
    public function testHasItem()
    {
        $this->basket->add($item = $this->testProduct->toBasketItem());
        $this->assertTrue($this->basket->hasBasketItem($item));
        $this->assertFalse($this->basket->hasBasketItem((new Product)->toBasketItem()));
    }

    /**
     *
     * @throws InvalidQuantityException
     */
    public function testAddPurchasableItemToBasket()
    {
        $this->assertCount(0, $this->basket->items());

        $item = $this->testProduct->toBasketItem();
        $this->basket->add($item);

        $this->assertCount(1, $this->basket->items());
        $this->basket->items()[$item->id()];
        $this->assertEquals($this->testProduct, $item->purchsable());

        // Adding the same Purchasable item to the basket again should increment the basket item
        // quantity.
        $this->basket->add($item);

        $this->assertCount(1, $this->basket->items());
        $this->assertEquals(2, $this->basket->items()[$item->id()]->quantity());
    }

    /**
     * @throws InvalidQuantityException
     */
    public function testRemovePurchasableItemFromBasketDecrements()
    {
        $item = $this->testProduct->toBasketItem();

        // Add two of the same basket item to the basket so we can test decrementing
        $this->basket
            ->add($item)
            ->add($item);

        // There should be one BasketItem which has the quantity of two.
        $this->assertCount(1, $this->basket->items());
        $this->assertEquals(2, $this->basket->items()[$item->id()]->quantity());

        // And now we can test decrementing the quantity
        $this->basket->remove($item);
        $this->assertCount(1, $this->basket->items()); // There should still be only on basket item
        $this->assertEquals(1, $this->basket->items()[$item->id()]->quantity()); // Which now has quantity of 1
    }

    /**
     * @throws Exception
     */
    public function testBasketItemTotal()
    {
        $basket = $this->modifyBasketItemCount(
            new Basket,
            $item = (new Product(['name' => 'Test Product', 'price' => $itemPrice = 100]))->toBasketItem(),
            $total = 10,
            'add'
        );

        // Test the total returned is the item price * by the quantity of items added
        $this->assertEquals($total * $itemPrice, $basket->items()[$item->id()]->total());
    }

    /**
     * @throws Exception
     */
    public function testBasketItemDiscountedTotal()
    {
        $basket = $this->modifyBasketItemCount(
            new Basket,
            $item = (new Product(['name' => 'Test Product', 'price' => $itemPrice = 100]))->toBasketItem(),
            $total = 10,
            'add'
        );

        // Test the total returned is the item price * by the quantity of items added
        $this->assertEquals($total * $itemPrice, $basket->items()[$item->id()]->discountedTotal());
    }

    /**
     * @throws Exception
     */
    public function testBasketItemQuantityZeroRemovedFromBasket()
    {
        // Add items to basket
        $basket = $this->modifyBasketItemCount(
            new Basket,
            $item = (new Product(['name' => 'Test Product', 'price' => $itemPrice = 100]))->toBasketItem(),
            $total = 10,
            'add'
        );

        // Test the total returned is the item price * by the quantity of items added
        $this->assertEquals($total, $basket->items()[$item->id()]->quantity());

        // Now decrement the quantity of the basket item till the value is zero.
        $basket = $this->modifyBasketItemCount($basket, $item, 10, 'remove');

        // And assert the item is no longer in the basket
        $this->assertEmpty($basket->items());
    }
}
