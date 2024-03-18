<?php

namespace RedJasmine\Product\Services\Property\Actions\Names;

use RedJasmine\Product\Services\Property\PropertyNameService;
use RedJasmine\Support\Foundation\Service\Actions;
use Spatie\QueryBuilder\AllowedFilter;

class PropertyNameQueryAction extends Actions\ResourceQueryAction
{

    public PropertyNameService $service;

    public function filters() : array
    {
        return [
            'name',
            AllowedFilter::exact('pid'),
            static::searchFilter([ 'name', 'pid' ])
        ];
    }


}
