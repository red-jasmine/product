<?php

namespace RedJasmine\Product\Services\Stock\Actions;


use RedJasmine\Product\Services\Stock\Data\StockActionData;

class StockInitAction extends AbstractStockAction
{

    protected ?string $dataClass = StockActionData::class;

    public function execute($data, bool $onlyLog = false)
    {
        $this->data = $this->conversionData($data);
        return $this->initStock($this->data, $onlyLog);
    }
}
