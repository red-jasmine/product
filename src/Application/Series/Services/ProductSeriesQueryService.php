<?php

namespace RedJasmine\Product\Application\Series\Services;

use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;

class ProductSeriesQueryService extends ApplicationQueryService
{
    public function __construct(
        protected ProductSeriesReadRepositoryInterface $repository
    )
    {
        parent::__construct();
    }


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('name'),

        ];
    }

    public function allowedIncludes() : array
    {
        return [ 'products' ];
    }
}