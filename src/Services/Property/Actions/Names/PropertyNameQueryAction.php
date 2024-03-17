<?php

namespace RedJasmine\Product\Services\Property\Actions\Names;

use RedJasmine\Support\Foundation\Service\Actions;
use Spatie\QueryBuilder\AllowedFilter;

class PropertyNameQueryAction extends Actions\AbstractQueryAction
{

    public function filters() : array
    {
        return [
            'name',
            AllowedFilter::exact('pid'),
            static::searchFilter([ 'name', 'pid' ])
        ];
    }


}
