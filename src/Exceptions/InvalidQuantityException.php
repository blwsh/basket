<?php namespace blwsh\basket;

use Exception;

/**
 * Class InvalidQuantityException
 */
class InvalidQuantityException extends Exception
{
    protected $message = 'Quantity must be least one.';
}
