<?php

namespace RedJasmine\Product\Application\Product\UserCases\Commands;

use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Optional;

class PropertyValue extends Data
{

    /**
     * 属性值ID 为0 是 输入类型
     * @var int|string|null
     */
    public null|int|string $vid;
    /**
     * 属性值
     * @var string|Optional
     */
    public string|Optional $name;

}
