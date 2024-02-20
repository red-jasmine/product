<?php

namespace RedJasmine\Product\Services\Series;

use Illuminate\Support\Collection;
use RedJasmine\Product\Models\ProductSeries;
use RedJasmine\Support\Foundation\Service\Service;

class ProductSeriesService extends Service
{

    public function create(string $name, Collection $products, string $remarks = null)
    {
        $productSeries = new ProductSeries();

        $productSeries->name = $name;


        $this->getOwner();
    }

}
