<?php

namespace Property\CommandHandlers;

use RedJasmine\Product\Application\Property\Services\ProductPropertyCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueCommandService;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;
use RedJasmine\Product\Tests\Fixtures\Property\ProductPropertyFaker;

class ProductPropertyValueCommandHandlersTest extends ApplicationTestCase
{


    public function propertyCommandService() : ProductPropertyCommandService
    {
        return app(ProductPropertyCommandService::class)->setOperator($this->user());
    }


    public function propertyRepository() : ProductPropertyRepositoryInterface
    {
        return app(ProductPropertyRepositoryInterface::class);
    }


    public function commandService() : ProductPropertyValueCommandService
    {
        return app(ProductPropertyValueCommandService::class)->setOperator($this->user());
    }


    public function repository() : ProductPropertyValueRepositoryInterface
    {
        return app(ProductPropertyValueRepositoryInterface::class);
    }


    public function test_can_create() : void
    {
        // 创建属性
        $command         = (new ProductPropertyFaker)->createCommand();
        $productProperty = $this->propertyCommandService()->create($command);
        $productProperty = $this->propertyRepository()->find($productProperty->id);
        $this->assertEquals($command->name, $productProperty->name);

    }


    public function test_can_update() : void
    {
        $command = (new ProductPropertyFaker)->createCommand();

        $productProperty = $this->commandService()->create($command);


        $command = (new ProductPropertyFaker)->updateCommand([ 'id' => $productProperty->id ]);

        $this->commandService()->update($command);

        $productProperty = $this->repository()->find($productProperty->id);

        $this->assertEquals($command->name, $productProperty->name);
        $this->assertEquals($command->sort, $productProperty->sort);


    }

}
