<?php

namespace RedJasmine\Product\Services\Property\Query;

use RedJasmine\Product\Models\ProductProperty;
use RedJasmine\Support\Traits\Services\HasQueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class PropertyQuery
{

    use HasQueryBuilder;

    protected string $model = ProductProperty::class;

    public function filters() : array
    {
        return [
            'name',
            AllowedFilter::exact('pid'),
            self::searchFilter([ 'name', 'pid' ])
        ];
    }


    public function find($id) : ProductProperty
    {
        return $this->query()->findOrFail($id);
    }

    public function lists() : \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()->paginate();
    }


}
