<?php

namespace RedJasmine\Product\Application\Product\UserCases\Commands;

use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Optional;

class PropertyValue extends Data
{

    public string|Optional $name;
    /**
     * 属性值
     * @var int|array|string
     */
    public int|array|string $vid;
}
