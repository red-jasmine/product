<?php

namespace RedJasmine\Product\Application\Category\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryDeleteCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryUpdateCommand;
use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\ProductCategoryRepository;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Helpers\ID\Snowflake;

// TODO 需要验证名称重复
/**
 * @method int create(ProductCategoryCreateCommand $command)
 * @method void update(ProductCategoryUpdateCommand $command)
 * @method void delete(ProductCategoryDeleteCommand $command)
 * @method ProductCategory find(int $id)
 */
class ProductCategoryCommandService extends ApplicationCommandService
{

    protected static string $modelClass = ProductCategory::class;

    public function __construct(protected ProductCategoryRepository $repository)
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
