<?php namespace blwsh\basket\Traits;

use DateTime;
use blwsh\basket\StockRecord;

/**
 * Trait HasStock
 *
 * @package blwsh\basket\Traits
 */
trait HasStock
{
    /**
     * @var StockRecord[]
     */
    protected array $stock = [];

    /**
     * @var bool If ignoreStockChecks is true, the following methods will always return early with these values:
     *           * isStockExpired: false
     *           * hasExpiringStock: false
     *           * hasStock: true
     */
    protected bool $ignoreStockChecks = false;

    /**
     * @param bool $ignore
     *
     * @return self
     */
    public function ignoreStockChecks($ignore = true): self
    {
        $this->ignoreStockChecks = $ignore;
        return $this;
    }

    /**
     * @return mixed
     */
    public function stock(): array
    {
        return $this->stock;
    }

    /**
     * @param StockRecord $record
     *
     * @return self
     */
    public function addStockRecord(StockRecord $record): self
    {
        $this->stock[] = $record;
        return $this;
    }

    /**
     * @param string $date
     *
     * @return DateTime
     */
    protected function parseStockDate(string $date): DateTime
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $date);
    }

    /**
     * @param string $date The date to check for stock
     *
     * @return bool Will return true if any stock records can be found which have:
     *              * a stock level of one or more
     *              * hasn't expired
     *              * has been stocked
     */
    public function hasStock(string $date): bool
    {
        if ($this->ignoreStockChecks) return true;

        foreach ($this->stock as $record) {
            if (
                $record->stock > 0
                && $date >= $record->stockedAt
                && $date <= $record->expiresAt
            ) return true;
        }

        return false;
    }

    /**
     * @param string $date The date to check for expiring expiring stock.
     *
     * @return bool Will return true if all stock is either
     *              * empty
     *              * or the expiration date has past
     */
    public function isStockExpired(string $date): bool
    {
        if ($this->ignoreStockChecks) return false;

        $expiredCount = 0;

        foreach ($this->stock as $record) {
            // If the stock level is zero or the expiration date has passed we can mark it as expired.
            if ($record->stock <= 0 || $date > $record->expiresAt) {
                $expiredCount++;
            }
        }

        // If all items have been marked as expired we can return true.
        return $expiredCount === count($this->stock);
    }

    /**
     * @param string $date The date to check for stock that will expire on the same day.
     *
     * @return bool Will return true if there are items in stock and the expiration date is equal to the date passed in
     *              via the date parameter.
     */
    public function hasExpiringStock(string $date): bool
    {
        $date = $this->parseStockDate($date)->format('Y-m-d');

        if ($this->ignoreStockChecks) return false;

        foreach ($this->stock as $record) {
            if (
                $record->stock > 0
                && $this->parseStockDate($record->expiresAt)->format('Y-m-d') === $date
            ) {
                return true;
            }
        }

        return false;
    }
}
