<?php

namespace RedJasmine\Product\Application\Category\Services\QueryHandlers;

use RedJasmine\Product\Application\Category\UserCases\Queries\ProductCategoryTreeQuery;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryReadRepositoryInterface;
use RedJasmine\Support\Application\QueryHandler;

class ProductCategoryTreeQueryHandler extends QueryHandler
{


    protected function repository() : ProductCategoryReadRepositoryInterface
    {
        return $this->getService()->getRepository();
    }


    public function handle(ProductCategoryTreeQuery $query)
    {
        return $this->repository()->tree($query);
    }

}
