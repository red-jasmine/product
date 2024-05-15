<?php

namespace RedJasmine\Product\Services\Property\Events;

use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Product\Domain\Property\Models\ProductProperty;

abstract class AbstractProductPropertyEvent
{
    use Dispatchable;

    public function __construct(public ProductProperty $productProperty)
    {
    }
}
