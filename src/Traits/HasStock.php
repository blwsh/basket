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
    protected array $stock;
    /**
     * If ignoreStockChecks is true, the following methods will always return early with these values:
     * isStockExpired: false
     * hasExpiringStock: false
     * hasStock: true
     *
     * @var bool
     */
    protected bool $ignoreStockChecks = false;

    /**
     * @param bool $ignore
     */
    public function ignoreStockChecks($ignore = true)
    {
        $this->ignoreStockChecks = $ignore;
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

    public function hasStock($date): bool
    {
        $date = $this->parseStockDate($date);

        if ($this->ignoreStockChecks) return true;

        return array_reduce($this->stock, function(StockRecord $record) use ($date) {
            return $record->stock > 0
                && $this->parseStockDate($record->expiresAt)->format('Y-m-d') === $date->format('Y-m-d');
        });
    }

    /**
     * @param string $date The date to check for expiring expiring stock.
     *
     * @return bool Returns true if the stock array contains a stock record where the expiresAt date is equal to the
     *              passed via the $date param.
     */
    public function isStockExpired(string $date): bool
    {
        $date = $this->parseStockDate($date);

        if ($this->ignoreStockChecks) return false;

        // Checks the stock array for any records that haven't expired yet and the stock value is more than zero.
        // If no values found which match this criteria then the stock has expired.0
    }

    /**
     * @param string $date
     *
     * @return bool
     */
    public function hasExpiringStock(string $date): bool
    {
        $date = $this->parseStockDate($date);

        if ($this->ignoreStockChecks) return false;

        return array_reduce($this->stock, function(StockRecord $record) use ($date) {
            return $record->stock > 0
                && $this->parseStockDate($record->expiresAt)->format('Y-m-d') === $date->format('Y-m-d');
        });
    }
}
