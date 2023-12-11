<?php

namespace RedJasmine\Product\Business\Buyer;

use Illuminate\Database\Eloquent\Builder;

class ProductService extends \RedJasmine\Product\Services\Product\ProductService
{
    /**
     * @return Builder
     */
    public function query() : Builder
    {
        $query = parent::query();
        return $query;
    }

}
