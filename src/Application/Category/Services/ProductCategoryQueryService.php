<?php

namespace RedJasmine\Product\Application\Category\Services;

use RedJasmine\Product\Application\Category\UserCases\Queries\ProductCategoryTreeQuery;
use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;


/**
 * @method ProductCategory find(int $id, ?FindQuery $query = null)
 */
class ProductCategoryQueryService extends ApplicationQueryService
{


    public function __construct(protected ProductCategoryReadRepositoryInterface $repository)
    {
        parent::__construct();
    }

    public function allowedFields() : array
    {
        return [
            'id',
            'parent_id',
            'name',
            'image',
            'group_name', 'sort',
            'is_leaf', 'is_show',
            'status', 'expands',
        ];

    }


    public function tree(ProductCategoryTreeQuery $query) : array
    {
        return $this->repository->tree($query);
    }


    public function isAllowUse(int $id) : bool
    {
        return (bool)($this->find($id)?->isAllowUse());
    }

}
