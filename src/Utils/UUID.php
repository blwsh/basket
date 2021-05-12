<?php namespace blwsh\basket\Utils;

use Exception;

/**
 * Class UUID
 *
 * @package blwsh\basket\Utils
 */
class UUID
{
    /**
     * @return string
     */
    public static function generate(): string
    {
        try {
            // Set id as random 20 char string
            return bin2hex(random_bytes(10));
        } catch (Exception) {
            // We're just going to ignore this for now. In reality, random_bytes will never throw an exception.
            // Maybe could add logging here or a retry loop with a circuit break in the future.
        }
    }
}
