<?php

namespace RedJasmine\Product\Services\Product\Events;

use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Product\Domain\Product\Models\Product;

abstract class AbstractProductEvent
{

    use Dispatchable;

    public function __construct(public Product $product)
    {

    }

}
