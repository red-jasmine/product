<?php

namespace RedJasmine\Product\Services\Series\Actions;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Models\ProductSeries;
use RedJasmine\Product\Models\ProductSeriesProduct;
use RedJasmine\Product\Services\Series\Data\ProductSeriesData;
use RedJasmine\Product\Services\Series\Data\ProductSeriesProductData;
use RedJasmine\Support\Foundation\Service\Actions\ResourceCreateAction;

/**
 * @property ProductSeries     $model
 * @property ProductSeriesData $data
 */
class ProductSeriesCreateAction extends ResourceCreateAction
{

    protected ?bool $hasDatabaseTransactions = true;


    protected function fill(array $data) : ?Model
    {
        $productSeries          = $this->model;
        $productSeries->name    = $this->data->name;
        $productSeries->owner   = $this->data->owner;
        $productSeries->creator = $this->service->getOperator();
        $productSeries->remarks = $this->data->remarks;
        return $this->model;
    }

    public function handle() : Model
    {
        $this->model->save();
        $productSeries = $this->model;
        $this->data->products->each(function (ProductSeriesProductData $productSeriesProductData) use ($productSeries) {
            $values = [
                'series_id'  => $productSeries->id,
                'product_id' => $productSeriesProductData->productId,
                'name'       => $productSeriesProductData->name,
            ];
            ProductSeriesProduct::updateOrCreate([ 'product_id' => $productSeriesProductData->productId ], $values);
        });
        return $productSeries;
    }


}
