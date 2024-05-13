<?php

namespace RedJasmine\Product\Application\Category\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryDeleteCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryUpdateCommand;
use RedJasmine\Product\Domain\Category\Models\ProductSellerCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductSellerCategoryRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Helpers\ID\Snowflake;


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

    public function newModel() : Model
    {
        $model                         = parent::newModel();
        $model->{$model->getKeyName()} = Snowflake::getInstance()->nextId();
        return $model;
    }


}
