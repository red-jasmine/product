<?php

namespace RedJasmine\Product\Application\Property\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Helpers\ID\Snowflake;

/**
 * @method ProductProperty create(ProductPropertyCreateCommand $command)
 * @method void update(ProductPropertyUpdateCommand $command)
 */
class ProductPropertyCommandService extends ApplicationCommandService
{
    protected static string $modelClass = ProductProperty::class;

    public function __construct(
        protected ProductPropertyRepositoryInterface $repository
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
