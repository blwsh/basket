<?php

/**
 * A simple dump and die function.
 */
if (!function_exists('dd')) {
    function dd(...$args) {
        foreach ($args as $arg) var_dump($arg);
        die;
    }
}

/**
 * Will pass string to DateTime class and output a datetime string.
 */
if (!function_exists('timestamp')) {
    function timestamp($date = null): string
    {
        return (new DateTime($date))->format('Y-m-d H:i:s');
    }
}
