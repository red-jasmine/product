<?php

namespace Property\CommandHandlers;

use RedJasmine\Product\Application\Property\Services\ProductPropertyCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueCommandService;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;
use RedJasmine\Product\Tests\Fixtures\Property\ProductPropertyFaker;
use RedJasmine\Product\Tests\Fixtures\Property\ProductPropertyValueFaker;

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


        $valueCreateCommand = (new ProductPropertyValueFaker())->createCommand([ 'pid' => $productProperty->id ]);

        $productPropertyValue = $this->commandService()->create($valueCreateCommand);

        $productPropertyValue = $this->repository()->find($productPropertyValue->id);


        $this->assertEquals($valueCreateCommand->pid, $productPropertyValue->pid);
        $this->assertEquals($valueCreateCommand->sort, $productPropertyValue->sort);
    }


    public function test_can_update() : void
    {
        // 创建属性
        $command         = (new ProductPropertyFaker)->createCommand();
        $productProperty = $this->propertyCommandService()->create($command);


        $valueCreateCommand = (new ProductPropertyValueFaker())->createCommand([ 'pid' => $productProperty->id ]);

        $productPropertyValue = $this->commandService()->create($valueCreateCommand);


        $valueUpdateCommand = (new ProductPropertyValueFaker())->updateCommand([ 'id' => $productPropertyValue->id ]);

        $this->commandService()->update($valueUpdateCommand);

        $productPropertyValue = $this->repository()->find($valueUpdateCommand->id);

        $this->assertEquals($valueUpdateCommand->id, $productPropertyValue->id);
        $this->assertEquals($valueUpdateCommand->name, $productPropertyValue->name);
        $this->assertEquals($valueUpdateCommand->sort, $productPropertyValue->sort);

    }

}
