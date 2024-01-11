<?php

namespace RedJasmine\Product\DataTransferObjects;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ProductProp extends Data
{

    public int       $pid;
    public int|array $vid;
    /**
     * @var DataCollection<ProductProp>|null
     */
    public ?DataCollection $options = null;
    public ?string         $name;

}
