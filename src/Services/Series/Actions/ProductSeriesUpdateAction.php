<?php

namespace RedJasmine\Product\Services\Series\Actions;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Models\ProductSeries;
use RedJasmine\Product\Models\ProductSeriesProduct;
use RedJasmine\Product\Services\Series\Data\ProductSeriesData;
use RedJasmine\Product\Services\Series\Data\ProductSeriesProductData;
use RedJasmine\Support\Foundation\Service\Actions\CreateAction;
use RedJasmine\Support\Foundation\Service\Actions\UpdateAction;

/**
 * @property ProductSeries     $model
 * @property ProductSeriesData $data
 */
class ProductSeriesUpdateAction extends UpdateAction
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
        $productSeries->products()->delete();
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
