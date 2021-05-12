<?php namespace blwsh\basket\Contracts;

interface Stockable
{
    public function hasStock(string $date): bool;
    public function isStockExpired(string $date): bool;
    public function hasExpiringStock(string $date): bool;
}
