<?php

namespace RedJasmine\Product\Application\Product\Services;

use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Repositories\ProductReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * @method Product findById(FindQuery $query)
 */
class ProductQueryService extends ApplicationQueryService
{


    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.product.query';

    public function __construct(
        protected ProductReadRepositoryInterface $repository
    ) {


    }

    public function allowedSorts() : array
    {
        return [
            AllowedSort::field('price'),
            AllowedSort::field('cost_price'),
            AllowedSort::field('market_price'),
            AllowedSort::field('sales'),
            AllowedSort::field('stock'),
            AllowedSort::field('on_sale_time'),
            AllowedSort::field('modified_time'),
        ];
    }


    public function allowedFilters() : array
    {

        return [
            AllowedFilter::partial('title'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('product_type'),
            AllowedFilter::exact('shipping_type'),
            AllowedFilter::exact('outer_id'),
            AllowedFilter::exact('is_multiple_spec'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('brand_id'),
            AllowedFilter::exact('category_id'),
            AllowedFilter::exact('product_group_id'),
        ];
    }

    public function allowedIncludes() : array
    {
        return [
            'extension',
            'skus',
            'brand',
            'category',
            'productGroups',
            'extendProductGroups',
            'series',
            'tags',
        ];
    }


}
