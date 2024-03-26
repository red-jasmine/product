<?php

namespace RedJasmine\Product\Services\Series\Actions;

use RedJasmine\Product\Models\ProductSeries;
use RedJasmine\Product\Services\Series\Data\ProductSeriesData;
use RedJasmine\Support\Foundation\Service\Actions\ResourceDeleteAction;

/**
 * @property ProductSeries     $model
 * @property ProductSeriesData $data
 */
class ProductSeriesDeleteAction extends ResourceDeleteAction
{

    protected ?bool $hasDatabaseTransactions = true;

    public function handle() : ?bool
    {
        $this->model->products()->delete();
        return $this->model->delete();
    }


}
