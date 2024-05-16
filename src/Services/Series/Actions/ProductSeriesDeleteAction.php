<?php

namespace RedJasmine\Product\Services\Series\Actions;

use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Services\Series\Data\ProductSeriesData;
use RedJasmine\Support\Foundation\Service\Actions\DeleteAction;

/**
 * @property \RedJasmine\Product\Domain\Series\Models\ProductSeries $model
 * @property ProductSeriesData                                      $data
 */
class ProductSeriesDeleteAction extends DeleteAction
{

    protected ?bool $hasDatabaseTransactions = true;

    public function handle() : ?bool
    {
        $this->model->products()->delete();
        return $this->model->delete();
    }


}
