<?php

namespace RedJasmine\Product\Services\Product\Actions;

use RedJasmine\Support\Foundation\Service\Actions\QueryAction;
use Spatie\QueryBuilder\AllowedFilter;

class ProductQueryAction extends QueryAction
{


    protected function filters() : array
    {
        return [ AllowedFilter::exact('id'),
                 AllowedFilter::exact('owner_type'),
                 AllowedFilter::exact('owner_id'),
                 AllowedFilter::exact('product_type'),
                 AllowedFilter::exact('shipping_type'),
                 AllowedFilter::partial('title'),
                 AllowedFilter::exact('outer_id'),
                 AllowedFilter::exact('is_multiple_spec'),
                 AllowedFilter::exact('status'),
                 AllowedFilter::exact('brand_id'),
                 AllowedFilter::exact('category_id'),
                 AllowedFilter::exact('seller_category_id'), ];
    }

    protected function includes() : array
    {
        return [
            'info', 'skus', 'skus.info', 'brand', 'category', 'sellerCategory', 'series'
        ];
    }

}
