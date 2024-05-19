<?php

namespace RedJasmine\Product\Application\Property\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Property\Services\Pipelines\ProductPropertyPipeline;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Data\Data;

/**
 * @method ProductProperty create(ProductPropertyCreateCommand $command)
 * @method void update(ProductPropertyUpdateCommand $command)
 */
class ProductPropertyCommandService extends ApplicationCommandService
{
    protected static string $modelClass = ProductProperty::class;

    protected ?string $pipelinesConfigKeyPrefix = 'pipelines.product.properties';

    protected function pipelines() : array
    {
        return [
            'create' => [

            ],
        ];
    }


    public function __construct(
        protected ProductPropertyRepositoryInterface $repository
    )
    {
        parent::__construct();
    }

    public function newModel($data = null) : Model
    {
        if ($model = $this->repository->findByName($data->name)) {
            return $model;
        }
        return parent::newModel($data);
    }


    public function delete(Data $data) : void
    {
        throw new \RuntimeException('does not support');
    }


}
