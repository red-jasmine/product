<?php

namespace RedJasmine\Product\Application\Product\UserCases\Commands;

use RedJasmine\Support\Data\Data;

class Property extends Data
{

    /**
     * 属性ID
     * @var int
     */
    public int $pid;

    /**
     * 属性值
     * @var int|array|string|null
     */
    public int|array|string|null $value;


}
