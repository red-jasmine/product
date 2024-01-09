<?php

namespace RedJasmine\Product\Services\Property\Query;

use RedJasmine\Product\Models\ProductPropertyValue;
use RedJasmine\Support\Foundation\Service\HasQueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class PropertyValueQuery
{

    use HasQueryBuilder;

    /**
     * @return \Spatie\QueryBuilder\QueryBuilder
     */
    public function query()
    {
        return $this->queryBuilder();
    }

    protected string $model = ProductPropertyValue::class;

    public function filters() : array
    {
        return [
            AllowedFilter::exact('pid'),
            self::searchFilter([ 'name' ])
        ];
    }


    public function find($id) : ProductPropertyValue
    {
        return $this->query()->findOrFail($id);
    }

    public function lists() : \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()->paginate();
    }


}
