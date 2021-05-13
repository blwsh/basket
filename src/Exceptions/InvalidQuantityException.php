<?php namespace blwsh\basket\Exceptions;

use Exception;

/**
 * Class InvalidQuantityException
 */
class InvalidQuantityException extends Exception
{
    protected $message = 'Quantity must be least one.';
}
