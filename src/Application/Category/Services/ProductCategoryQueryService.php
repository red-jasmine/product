<?php

namespace RedJasmine\Product\Application\Category\Services;

use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;


class ProductCategoryQueryService extends ApplicationQueryService
{

    public function __construct(protected ProductCategoryReadRepositoryInterface $repository)
    {
        parent::__construct();
    }

}
