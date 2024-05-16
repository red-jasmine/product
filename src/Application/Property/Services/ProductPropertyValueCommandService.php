<?php

namespace RedJasmine\Product\Application\Property\Services;

use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Data\Data;

/**
 * @method ProductPropertyValue create(ProductPropertyValueCreateCommand $command)
 * @method void update(ProductPropertyValueUpdateCommand $command)
 */
class ProductPropertyValueCommandService extends ApplicationCommandService
{
    protected static string $modelClass = ProductPropertyValue::class;

    public function __construct(
        protected ProductPropertyValueRepositoryInterface $repository
    )
    {
        parent::__construct();
    }


    public function delete(Data $data) : void
    {
        throw new \RuntimeException('does not support');
    }


}
