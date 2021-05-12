<?php namespace blwsh\basket\Contracts;

use blwsh\basket\BasketItem;

/**
 * Interface Purchasable
 *
 * Currently, only the Product class implements the Purchasable interface however,
 * any class that implements Purchasable can be added to a Basket.
 *
 * @package blwsh\basket\Contracts
 */
interface Purchasable
{
    /**
     * @return int
     */
    public function getPrice(): int;
    public function toBasketItem(): BasketItem;
}
