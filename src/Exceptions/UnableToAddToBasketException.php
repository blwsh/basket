<?php namespace blwsh\basket\Exceptions;

use Exception;

/**
 * Class UnableToAddToBasketException
 */
class UnableToAddToBasketException extends Exception
{
    protected $message = "We're sorry but this item can not be added to your basket because it is out of stock.";
}
