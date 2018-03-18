<?php

namespace Ferrisbane\Basket;

class BasketItem
{

    protected $attributes = [];
    protected $optionKeys = [];

    /**
     * The array of booted classes.
     *
     * @var array
     */
    protected $booted = [];


    public function __construct(
        $attributes = []
    ) {
        $this->attributes = $attributes;

        $data = $this->bootIfNotBooted($this);
        $this->attributes = $data->attributes;
        $this->optionKeys = $data->optionKeys;
    }

    /**
     * Check if the class needs to be booted and if so, do it.
     *
     * @return void
     */
    protected function bootIfNotBooted($data)
    {
        if ( ! isset($this->booted[static::class])) {
            $this->booted[static::class] = true;

            return static::boot($data);
        }

        return $data;
    }

    /**
     * The "booting" method of the class.
     *
     * @return void
     */
    protected static function boot($data)
    {
        if (array_key_exists('options', $data->attributes)) {
            $options = [];
            foreach ($data->attributes['options'] as $key => $option) {
                $data->optionKeys[$option['key']] = $key;
                if ($option instanceof BasketItem) {
                    $options[] = $option;
                } else {
                    $options[] = new BasketItem($option);
                }
            }

            $data->attributes['options'] = $options;
        }

        return $data;
    }

    /**
     * Returns the key.
     *
     * @return
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Returns the price.
     *
     * @return float
     */
    public function price()
    {
        return (float) $this->price;
    }

    /**
     * Returns the total price.
     *
     * @return float
     */
    public function totalPrice()
    {
        return (float) bcmul($this->price, $this->quantity, 2);
    }

    /**
     * Returns if the basketitem has any options
     *
     * @return bool
     */
    public function hasOptions()
    {
        return is_array($this->options) && (count($this->options) > 0);
    }


    public function hasOption($key)
    {
        if ($this->hasOptions() && array_key_exists($key, $this->optionKeys)) {
            return true;
        }

        return false;
    }

    public function getOption($key)
    {
        if ($this->hasOption($key)) {
            $optionKey = $this->optionKeys[$key];
            return $this->options[$optionKey];
        }

        return false;
    }


    /**
     * Returns all options
     *
     * @return array
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * Dynamically retrieve attributes on the basket item.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return;
    }

    /**
     * Dynamically set attributes on the basket item.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function __toString()
    {
        return $this->toJson();
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function toArray()
    {
        $attributes = $this->attributes;

        if ($this->hasOptions()) {
            $options = [];
            foreach ($this->options() as $key => $option) {
                $options[] = $option->toArray();
            }

            $attributes['options'] = $options;
        }

        return $attributes;
    }
}