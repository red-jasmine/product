<?php

namespace RedJasmine\Product\Services\Stock\Actions;


use RedJasmine\Product\Services\Stock\Data\StockActionData;

class StockResetAction extends AbstractStockAction
{

    protected ?string $dataClass = StockActionData::class;

    public function execute($data)
    {
        $this->data = $this->conversionData($data);
        return $this->reset($this->data);
    }
}
