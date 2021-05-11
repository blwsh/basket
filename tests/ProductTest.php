<?php namespace blwsh\basket\Tests;

use blwsh\basket\Product;
use PHPUnit\Framework\TestCase;

/**
 * Class ProductTest
 *
 * @package blwsh\basket\Tests
 */
class ProductTest extends TestCase
{
    /**
     * Test setting attributes for the price class.
     */
    public function testSetProductAttributes()
    {
        $product = new Product;
        $product->name = $name = 'Fish and Chips';
        $product->price = $price = 500; // We use unit price notation as it conforms better with most payment gateways.
                                        // So 500 would be 500 pence (£5.00)®

        $this->assertEquals($name, $product->name);
        $this->assertEquals($price, $product->price);
    }

    /**
     * Test IDs generated for price class.
     */
    public function testProductAutoAssignedId()
    {
        $product = new Product;
        $this->assertTrue(strlen($product->id) === 20); // We expect a twenty char string.

        $newProduct = new Product;
        $this->assertNotEquals($product->id, $newProduct->id); // And ids should be unique.
    }
}
