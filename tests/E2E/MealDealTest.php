<?php namespace blwsh\basket\Tests\E2E;

use blwsh\basket\Product;
use PHPUnit\Framework\TestCase;

class MealDealTest extends TestCase
{
    public function testDiscountApplied()
    {
        $chips = new Product(['name' => 'Chips', 'price' => 180]);
        $pie = new Product(['name' => 'Pie', 'price' => 320]);
    }

    public function testDiscountAppliedToMultiple()
    {

    }

    public function testDiscountDoesNotApplyToInvalid()
    {

    }

    public function testDiscountsDoNotApplyWhenMealDeal()
    {

    }

    public function testOfferedLowestDiscountPrice()
    {

    }
}
