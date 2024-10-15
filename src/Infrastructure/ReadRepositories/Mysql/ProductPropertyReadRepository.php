<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\BaseReadRepository;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ProductPropertyReadRepository extends QueryBuilderReadRepository implements ProductPropertyReadRepositoryInterface
{

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = ProductProperty::class;

    /**
     * @param array $ids
     *
     * @return ProductProperty[]||null
     */
    public function findByIds(array $ids)
    {
        return static::$modelClass::query()->whereIn('id', $ids)->get();
    }


}
