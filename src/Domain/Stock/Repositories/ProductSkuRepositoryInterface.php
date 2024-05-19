<?php

namespace RedJasmine\Product\Domain\Stock\Repositories;

use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface ProductSkuRepositoryInterface extends RepositoryInterface
{


    public function log(ProductStockLog $log) : void;
}
