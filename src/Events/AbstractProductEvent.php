<?php

namespace RedJasmine\Product\Events;

use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Product\Models\Product;

abstract class AbstractProductEvent
{
    use Dispatchable;

    public function __construct(protected Product $product)
    {
    }
}
