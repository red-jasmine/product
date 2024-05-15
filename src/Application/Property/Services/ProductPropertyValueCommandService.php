<?php

namespace RedJasmine\Product\Application\Property\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyGroup;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Helpers\ID\Snowflake;

/**
 * @method ProductPropertyValue create(ProductPropertyValueCreateCommand $command)
 * @method void update(ProductPropertyValueUpdateCommand $command)
 */
class ProductPropertyValueCommandService extends ApplicationCommandService
{
    protected static string $modelClass = ProductPropertyGroup::class;

    public function __construct(
        protected ProductPropertyGroupRepositoryInterface $repository
    )
    {
        parent::__construct();
    }


    public function newModel() : Model
    {
        $model                         = parent::newModel();
        $model->{$model->getKeyName()} = Snowflake::getInstance()->nextId();
        return $model;
    }


    public function delete(Data $data) : void
    {
        throw new \RuntimeException('does not support');
    }


}
