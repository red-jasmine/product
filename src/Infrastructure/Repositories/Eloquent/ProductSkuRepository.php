<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;

use RedJasmine\Product\Domain\Stock\Models\ProductSku;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ProductSkuRepository extends EloquentRepository implements ProductSkuRepositoryInterface
{
    protected static string $eloquentModelClass = ProductSku::class;

    public function log(ProductStockLog $log) : void
    {
        $log->save();
    }


}
