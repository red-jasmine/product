<?php

namespace RedJasmine\Product\Application\Stock\UserCases;


use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Support\Data\Data;

class StockInitCommand extends Data
{

    public int                        $skuId;
    public int                        $productId;
    public int                        $stock;
    public bool                       $isLock       = false;
    public ProductStockChangeTypeEnum $changeType   = ProductStockChangeTypeEnum::SALE;
    public ?string                    $changeDetail = null;
    public ?string                    $channelType  = null;
    public ?int                       $channelId    = null;

}
