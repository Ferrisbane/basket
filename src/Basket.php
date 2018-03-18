<?php

namespace Ferrisbane\Basket;

use Ferrisbane\Basket\Contracts\Basket as BasketC;
use Illuminate\Session\Store as Session;
use Ferrisbane\Basket\BasketItem;

class Basket implements BasketC
{
    protected $items = false;
    protected $itemKeys = [];
    protected $session;

    public function __construct(
        Session $session
    ) {
        $this->session = $session;
    }

    public function bootIfNotBooted()
    {
        if ( ! isset($this->items) || ! $this->items) {
            $items = $this->session->get('ferrisane.basket.items');

            $this->items = [];
            if ( ! empty($items)) {
                foreach ($items as $key => $item) {
                    $basketItem = new BasketItem($item);
                    $this->items[] = $basketItem;
                    $this->itemKeys[$basketItem->key()] = $key;
                }
            }
        }
    }

    public function add($items)
    {
        foreach ($items as $key => $item) {

            if ($item instanceof BasketItem) {
                $itemKey = $item->key();
            } elseif (is_array($item)) {
                $itemKey = $item['key'];
            } else {
                continue;
            }

            if ($this->hasItem($itemKey)) {
                $basketKey = $this->itemKeys[$itemKey];
                $this->items[$basketKey] = $item;
            } else {
                $this->itemKeys[$itemKey] = count($this->items);
                $this->items[] = $item;
            }
        }

        $this->save();
    }

    public function update($key, $item)
    {
        if ( ! $this->hasItem($key)) {
            return false;
        }

        $itemKey = $this->itemKeys[$key];
        $this->items[$itemKey] = $item;

        $this->save();

        return true;
    }

    public function remove($key)
    {
        if ($this->hasItem($key)) {
            $itemKey = $this->itemKeys[$key];

            unset($this->items[$itemKey]);

            $this->save();

            return true;
        }

        return false;
    }

    public function hasItems()
    {
        return isset($this->items) && $this->items;
    }

    public function items()
    {
        $this->bootIfNotBooted();

        return $this->items;
    }

    public function hasItem($key)
    {
        if ($this->hasItems() && array_key_exists($key, $this->itemKeys)) {
            return true;
        }

        return false;
    }

    public function getItem($key)
    {
        if ($this->hasItem($key)) {
            $itemKey = $this->itemKeys[$key];
            return $this->items[$itemKey];
        }

        return false;
    }

    public function count()
    {
        return count($this->items());
    }

    public function totalPrice()
    {
        $items = $this->items();

        $total = 0.00;
        foreach ($items as $itemKey => $item) {
            $total = bcadd($total, $item->totalPrice(), 2);

            if ($item->hasOptions()) {
                foreach ($item->options as $optionKey => $option) {
                    $total = bcadd($total, $option->totalPrice(), 2);
                }
            }
        }

        return $total;
    }

    protected function caculateTotal($price, $quantity)
    {
        return bcmul($price, $quantity, 2);
    }

    public function emptyBasket()
    {
        $this->items = [];
        $this->itemKeys = [];

        $this->save();

        return true;
    }

    public function save()
    {
        $basketItems = [];
        foreach ($this->items as $key => $item) {
            if ($item instanceof BasketItem) {
                $basketItems[] = $item->toArray();
            } elseif (is_array($item)) {
                $basketItems[] = $item;
            }
        }

        $this->session->put([
            'ferrisane.basket.items' => $basketItems
        ]);

        $this->session->save();
    }
}