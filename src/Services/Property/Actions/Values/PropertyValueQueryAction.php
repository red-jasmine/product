<?php

namespace RedJasmine\Product\Services\Property\Actions\Values;

use RedJasmine\Support\Foundation\Service\Actions;
use Spatie\QueryBuilder\AllowedFilter;

class PropertyValueQueryAction extends Actions\ResourceQueryAction
{

    public function filters() : array
    {
        return [
            'name',
            AllowedFilter::exact('pid'),
            self::searchFilter([ 'name', 'vid' ])
        ];
    }


}
