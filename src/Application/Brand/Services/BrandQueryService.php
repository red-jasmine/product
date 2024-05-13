<?php

namespace RedJasmine\Product\Application\Brand\Services;

use RedJasmine\Product\Domain\Brand\Repositories\BrandReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;

class BrandQueryService extends ApplicationQueryService
{
    public function __construct(protected BrandReadRepositoryInterface $repository)
    {
        parent::__construct();
    }


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('initial'),
            AllowedFilter::partial('name'),
            AllowedFilter::partial('english_name'),

        ];
    }


}
