<?php

namespace RedJasmine\Product\Services\Product;

use RedJasmine\Product\Models\Product;
use RedJasmine\Support\Foundation\Service\HasQueryBuilder;
use RedJasmine\Support\Traits\Services\ServiceExtends;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductQuery
{
    use HasQueryBuilder;


    use ServiceExtends;

    public function __construct(protected ?ProductService $service)
    {
    }

    /**
     *
     * @return QueryBuilder|Product
     */
    public function query() : QueryBuilder
    {
        $query = $this->queryBuilder();
        return $query;
    }


    public function lists() : \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()->paginate();
    }

    /**
     * @param $id
     *
     * @return Product
     */
    public function find($id) : Product
    {
        return $this->query()->findOrFail($id);
    }


    public string $model = Product::class;


    public function filters() : array
    {
        return [
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
            static::searchFilter([ 'title' ])
        ];
    }


    public function includes() : array
    {
        return [
            'info', 'skus', 'skus.info', 'brand', 'category', 'sellerCategory','series'
        ];
    }


}
