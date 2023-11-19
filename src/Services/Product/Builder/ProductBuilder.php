<?php

namespace RedJasmine\Product\Services\Product\Builder;

use Exception;
use RedJasmine\Product\Product\Contracts\ProductBuilderInterface;
use RedJasmine\Support\Helpers\ID\Snowflake;

class ProductBuilder implements ProductBuilderInterface
{
    /**
     * 生成 ID
     * @return int
     * @throws Exception
     */
    public function generateID() : int
    {
        return Snowflake::getInstance()->nextId();
    }


}
