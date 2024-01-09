<?php

namespace RedJasmine\Product\Business\Seller;

use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductQuery extends \RedJasmine\Product\Services\Product\ProductQuery
{


    public function query() : QueryBuilder
    {
        $query = parent::query();

        $query->onlyOwner($this->service->getOwner());
        return $query;
    }

    public function filters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('product_type'),
            AllowedFilter::exact('shipping_type'),
            AllowedFilter::partial('title'),
            AllowedFilter::exact('outer_id'),
            AllowedFilter::exact('is_multiple_spec'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('brand_id'),
            AllowedFilter::exact('category_id'),
            AllowedFilter::exact('seller_category_id'),
            static::searchFilter([ 'title', 'keywords' ])
        ];
    }


}
