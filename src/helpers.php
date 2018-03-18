<?php

if ( ! function_exists('ferrisbaneBasket')) {

    /**
     * Get an instance of the ferrisbane basket class.
     *
     * @return FerrisbaneBasket
     */
    function ferrisbaneBasket()
    {
        return app('ferrisbane.basket');
    }
}

