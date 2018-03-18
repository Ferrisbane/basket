<?php

namespace Ferrisbane\Basket\Facades;

class Basket
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ferrisbane.basket';
    }
}