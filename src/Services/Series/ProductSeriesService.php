<?php

namespace RedJasmine\Product\Services\Series;


use RedJasmine\Product\Models\ProductSeries;
use RedJasmine\Product\Services\Series\Actions\ProductSeriesCreateAction;
use RedJasmine\Product\Services\Series\Actions\ProductSeriesDeleteAction;
use RedJasmine\Product\Services\Series\Actions\ProductSeriesUpdateAction;
use RedJasmine\Product\Services\Series\Data\ProductSeriesData;
use RedJasmine\Support\Foundation\Service\ResourceService;

class ProductSeriesService extends ResourceService
{

    protected static string $dataClass = ProductSeriesData::class;


    protected static string $modelClass = ProductSeries::class;


    protected function actions() : array
    {
        $actions           = parent::actions();
        $actions['create'] = ProductSeriesCreateAction::class;
        $actions['update'] = ProductSeriesUpdateAction::class;
        $actions['delete'] = ProductSeriesDeleteAction::class;
        return $actions;
    }


}
