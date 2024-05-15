<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\BaseReadRepository;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ProductPropertyValueReadRepository extends QueryBuilderReadRepository implements ProductPropertyValueReadRepositoryInterface
{

    /**
     * @var $modelClass class-string
     */
    protected string $modelClass = ProductPropertyValue::class;

}
