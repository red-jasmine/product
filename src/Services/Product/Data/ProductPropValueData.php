<?php

namespace RedJasmine\Product\Services\Product\Data;

use RedJasmine\Support\DataTransferObjects\Data;
use Spatie\LaravelData\Optional;

class ProductPropValueData extends Data
{

    public string|Optional $name;
    /**
     * 属性值
     * @var int|array
     */
    public int|array $vid;
}
