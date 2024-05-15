<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;


use RedJasmine\Product\Domain\Property\Models\ProductPropertyGroup;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;


class ProductPropertyGroupRepository extends EloquentRepository implements ProductPropertyGroupRepositoryInterface
{
    protected static string $eloquentModelClass = ProductPropertyGroup::class;

}
