<?php

namespace RedJasmine\Product\Facades;

use Illuminate\Support\Facades\Facade;

class Product extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'product';
    }
}
