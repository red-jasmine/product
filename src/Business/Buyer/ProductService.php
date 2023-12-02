<?php

namespace RedJasmine\Product\Business\Buyer;

use Spatie\QueryBuilder\QueryBuilder;

class ProductService extends \RedJasmine\Product\Services\Product\ProductService
{
    /**
     * @return QueryBuilder
     */
    public function query() : QueryBuilder
    {
        $query = parent::query();
        return $query;
    }

}
