<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;

use RedJasmine\Product\Domain\Category\Models\ProductSellerCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductSellerCategoryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ProductSellerCategoryRepository extends EloquentRepository implements ProductSellerCategoryRepositoryInterface
{

    protected static string $eloquentModelClass = ProductSellerCategory::class;

}
