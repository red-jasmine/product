<?php

use Spatie\QueryBuilder\AllowedFilter;

return [
    // 服务操作配置
    'services' => [
        'product'  => [
            'actions' => [
                'query' => [
                    'class'    => \RedJasmine\Product\Services\Product\Actions\ProductQueryAction::class,
                    'filters'  => [
                        AllowedFilter::exact('id'),
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
                        AllowedFilter::exact('seller_category_id'),
                    ],
                    'includes' => [
                        'info', 'skus', 'skus.info', 'brand', 'category', 'sellerCategory', 'series'
                    ],
                    'fields'   => [],
                    'sorts'    => [],
                ],
            ],
        ],
        'property' => [
            'group' => [
                'actions' => null,
            ],
            'name'  => [
                'actions' => null,
            ],
        ],

        'value'           => [
            'actions'   => [],
            'pipelines' => [
            ],
        ],
        'brand'           => [
            'actions'   => [],
            'pipelines' => [

            ],
        ],
        'category'        => [
            'actions'   => [],
            'pipelines' => [

            ],
        ],
        'seller-category' => [
            'actions'   => [],
            'pipelines' => [

            ],
        ],
    ],


];
