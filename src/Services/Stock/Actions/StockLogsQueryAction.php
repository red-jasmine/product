<?php

namespace RedJasmine\Product\Services\Stock\Actions;

use RedJasmine\Product\Models\ProductStockLog;
use RedJasmine\Support\Foundation\Service\Actions\QueryAction;

class StockLogsQueryAction extends QueryAction
{

    protected ?string $modelClass = ProductStockLog::class;

    protected function filters() : array
    {
        return [
            'sku_id',
            'type',
            'change_type'
        ];
    }


}
