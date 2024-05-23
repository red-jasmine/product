<?php

namespace RedJasmine\Product\Application\Category\Services;

use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryDeleteCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryUpdateCommand;
use RedJasmine\Product\Domain\Category\Models\ProductSellerCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductSellerCategoryRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;


/**
 * @method int create(ProductSellerCategoryCreateCommand $command)
 * @method void update(ProductSellerCategoryUpdateCommand $command)
 * @method void delete(ProductSellerCategoryDeleteCommand $command)
 * @method ProductSellerCategory find(int $id)
 */
class ProductSellerCategoryCommandService extends ApplicationCommandService
{

    protected static string $modelClass = ProductSellerCategory::class;

    public function __construct(protected ProductSellerCategoryRepositoryInterface $repository)
    {
        parent::__construct();
    }


}
