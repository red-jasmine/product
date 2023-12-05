<?php

namespace RedJasmine\Product\Services\Property\Query;

use RedJasmine\Product\Models\ProductProperty;
use RedJasmine\Product\Models\ProductPropertyValue;
use RedJasmine\Support\Traits\Services\HasQueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class PropertyValueQuery
{

    use HasQueryBuilder;

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
