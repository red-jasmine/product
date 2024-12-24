<?php

namespace RedJasmine\Product\Application\Product\UserCases\Commands;

use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Data\Data;

class ProductSetStatusCommand extends Data
{

    public int $id;


    public ProductStatusEnum $status;

}
