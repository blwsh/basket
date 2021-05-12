<?php namespace blwsh\basket;

class StockRecord
{
    /**
     * StockRecord constructor.
     *
     * @param int    $stock The amount of items in stock
     * @param string $stockedAt Format: Y-m-d H:i:s
     * @param string $expiresAt Format: Y-m-d H:i:s
     */
    public function __construct(
        public int $stock,
        public string $stockedAt,
        public string $expiresAt,
    ) {}
}
