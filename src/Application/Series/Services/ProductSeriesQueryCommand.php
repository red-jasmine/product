<?php

namespace RedJasmine\Product\Application\Series\Services;

use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;

class ProductSeriesQueryCommand extends ApplicationQueryService
{
    public function __construct(
        protected ProductSeriesReadRepositoryInterface $repository
    )
    {
        parent::__construct();
    }


}
