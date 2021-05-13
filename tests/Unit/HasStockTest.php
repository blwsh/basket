<?php namespace blwsh\basket\Tests\Unit;

use blwsh\basket\Product;
use blwsh\basket\StockRecord;
use PHPUnit\Framework\TestCase;

/**
 * Class HasStockTest
 *
 * @package blwsh\basket\Tests\Unit
 */
class HasStockTest extends TestCase
{
    /**
     * @param int  $stock
     * @param null $expires
     *
     * @return Product
     */
    public function product($stock = 1, $expires = null): Product
    {
        return new Product([
            'name' => 'Test Product',
            'price' => 100,
            'stock' => [
                new StockRecord($stock, timestamp('now'), $expires ?: timestamp('tomorrow'))
            ]
        ]);
    }

    /**
     *
     */
    public function testHasStock()
    {
        $this->assertTrue($this->product()->hasStock(timestamp('now')));
    }

    /**
     *
     */
    public function testOutOfStock()
    {
        $this->assertFalse($this->product(0)->hasStock(timestamp('now')));
    }

    /**
     *
     */
    public function testNotInStockYet()
    {
        $this->assertFalse($this->product()->hasStock(timestamp('-1 hour')));
    }

    /**
     *
     */
    public function testIsStockExpired()
    {
        $this->assertTrue($this->product(0, timestamp('-1 hour'))->isStockExpired(timestamp('now')));
        $this->assertTrue($this->product(1, timestamp('-1 hour'))->isStockExpired(timestamp('now')));
    }

    public function testIsStockExpiredFalse()
    {
        $this->assertFalse($this->product(1, timestamp('+1 hour'))->isStockExpired(timestamp('now')));
    }

    /**
     *
     */
    public function testHasExpiringStock()
    {
        $this->assertTrue($this->product(1, timestamp('+1 hour'))->hasExpiringStock(timestamp('now')));
        $this->assertTrue($this->product(1, timestamp('-1 hour'))->hasExpiringStock(timestamp('now')));
    }

    /**
     *
     */
    public function testHasExpiringStockFalse()
    {
        $this->assertFalse($this->product(1, timestamp('tomorrow'))->hasExpiringStock(timestamp('now')));
    }
}
