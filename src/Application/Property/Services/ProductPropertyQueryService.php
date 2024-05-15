<?php

namespace RedJasmine\Product\Application\Property\Services;

use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;


class ProductPropertyQueryService extends ApplicationQueryService
{
    public function __construct(
        protected ProductPropertyReadRepositoryInterface $repository
    )
    {
        parent::__construct();
    }


}
