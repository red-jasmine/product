<?php

namespace RedJasmine\Product\DataTransferObjects;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ProductPropValueDTO extends Data
{

    public string|Optional $name;
    /**
     * 属性值
     * @var int|array
     */
    public int|array $vid;
}
