<?php

namespace RedJasmine\Product\Application\Brand\Services;

use RedJasmine\Product\Domain\Brand\Repositories\BrandReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;

class BrandQueryService extends ApplicationQueryService
{
    public function __construct(protected BrandReadRepositoryInterface $repository)
    {
        parent::__construct();
    }


}
