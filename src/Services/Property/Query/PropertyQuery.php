<?php

namespace RedJasmine\Product\Services\Property\Query;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Models\ProductProperty;
use RedJasmine\Support\Foundation\Service\HasQueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PropertyQuery
{

    use HasQueryBuilder;




    /**
     * @return QueryBuilder
     */
    public function query() : QueryBuilder
    {
        return $this->queryBuilder();
    }


    public function filters() : array
    {
        return [
            'name',
            AllowedFilter::exact('pid'),
            self::searchFilter([ 'name', 'pid' ])
        ];
    }


    public function find($id) : Model
    {
        return $this->query()->findOrFail($id);
    }

    public function lists() : LengthAwarePaginator
    {
        return $this->query()->paginate();
    }


}
