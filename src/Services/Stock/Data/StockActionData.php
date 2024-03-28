<?php

namespace RedJasmine\Product\Services\Stock\Data;


use RedJasmine\Product\Services\Stock\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Support\Data\Data;

class StockActionData extends Data
{

    public int                        $skuId;
    public int                        $productId;
    public int                        $stock;
    public bool                       $isLock       = false;
    public ProductStockChangeTypeEnum $changeType   = ProductStockChangeTypeEnum::SALE;
    public string                     $changeDetail = '';
    public ?StockChannelData          $channel      = null;

}
