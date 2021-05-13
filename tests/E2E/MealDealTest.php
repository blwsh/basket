<?php namespace blwsh\basket\Tests\E2E;

use blwsh\basket\Basket;
use blwsh\basket\BasketItem;
use blwsh\basket\DiscountPolicy;
use blwsh\basket\Product;
use blwsh\basket\StockRecord;
use PHPUnit\Framework\TestCase;

class MealDealTest extends TestCase
{
    /**
     * @var Basket
     */
    protected Basket $basket;

    /**
     * @var Product
     */
    protected Product $chips;

    /**
     * @var Product
     */
    protected Product $pie;

    /**
     * @var DiscountPolicy
     */
    protected DiscountPolicy $mealDeal;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basket = new Basket;
        $this->chips = new Product(['name' => 'Chips', 'price' => 180, 'stock' => [new StockRecord(1, timestamp('-1 day'), timestamp('+2 day'))]]);
        $this->pie = new Product(['name' => 'Pie', 'price' => 320, 'stock' => [new StockRecord(1, timestamp('-1 day'), timestamp('+2 day'))]]);
        $this->mealDeal = new DiscountPolicy('20% off pie and chips', 20, 'percentage', [
            // In reality this method would be a predefined rule which would be more generic, however, because
            // we have no datastore, it's much easier to just go off of names for now.
            function(BasketItem $item) {
                $pieCount = 0;
                $chipsCount = 0;

                if (!$item->basket()) return false; // The item has not been added to a basket yet so we can return false.

                foreach ($item->basket()->items() as $item) {
                    if ($item->purchsable()->name === 'Pie') $pieCount += $item->quantity();
                    if ($item->purchsable()->name === 'Chips') $chipsCount += $item->quantity();
                }

                return $pieCount + $chipsCount !== 0 && $pieCount === $chipsCount;
            }
        ]);
    }

    public function testDiscountApplied()
    {
        // For the purpose of this test we directly add the meal deal DiscountPolicy to the basket items.
        // In a real world application, it's a much better idea to have an event listener do this or apply
        // discounts via the basket which come form a global store.
        $this->basket->add($chipsBasketItem = $this->chips->toBasketItem());
        $chipsBasketItem->applyDiscount($this->mealDeal);

        // Because there are only chips in our basket there should be no discount on the basket item.
        $this->assertEquals(180, $chipsBasketItem->discountedTotal());
        $this->assertEquals(180, $chipsBasketItem->total());

        return $chipsBasketItem;
    }

    public function testDiscountAppliedToMultiple()
    {
        // Adds chips to the basket.
        $chipsBasketItem = $this->testDiscountApplied();

        // Add a pie to our basket.
        $this->basket->add($pieBasketItem = $this->pie->toBasketItem());
        $pieBasketItem->applyDiscount($this->mealDeal);

        // Now we've added a pie to our basket both the pies and chips should have a 20% discount.
        $this->assertEquals(256, $pieBasketItem->discountedTotal());
        $this->assertEquals(320, $pieBasketItem->total());
        $this->assertEquals(144, $chipsBasketItem->discountedTotal());
        $this->assertEquals(180, $chipsBasketItem->total());
    }

    public function testOfferedLowestDistinctDiscountPrice()
    {
        $lowestOnlyRule = function(BasketItem $item, DiscountPolicy $rootPolicy) {
            if (!$item->basket()) return false;

            $hasDistinct = false;
            $bestOfferInt = 0;
            $bestOfferPolicy = null;

            foreach ($item->basket()->items() as $item) {
                foreach ($item->discountPolicies() as $policy) {
                    if ($policy->isDistinct()) {
                        $hasDistinct = true;
                    }

                    if ($deduction = $policy->getDeduction($item, true) > $bestOfferInt) {
                        $bestOfferInt = $deduction;
                        $bestOfferPolicy = $policy;
                    }
                }
            }

            return $hasDistinct && $bestOfferPolicy === $rootPolicy;
        };

        $onePoundOff = new DiscountPolicy('£1 off', 100, 'fixed', [$lowestOnlyRule]);
        $tenPercentOff = new DiscountPolicy('10% off', 10, 'percentage', [$lowestOnlyRule]);

        $chipsBasketItem = $this->chips->toBasketItem();
        $this->basket->add($chipsBasketItem);
        $chipsBasketItem->applyDiscount($onePoundOff)->applyDiscount($tenPercentOff);

        $this->assertEquals(80, $chipsBasketItem->discountedTotal());
    }
}
