<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Product\Domain\Property\Models\ProductPropertyGroup;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\BaseReadRepository;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ProductPropertyGroupReadRepository extends QueryBuilderReadRepository implements ProductPropertyGroupReadRepositoryInterface
{

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = ProductPropertyGroup::class;

}
