<?php namespace blwsh\basket\Contracts;

use blwsh\basket\StockRecord;

interface Stockable
{
    public function stock(): array;
    public function hasStock(string $date): bool;
    public function isStockExpired(string $date): bool;
    public function hasExpiringStock(string $date): bool;
    public function addStockRecord(StockRecord $record): mixed;
}
