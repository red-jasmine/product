<?php

namespace RedJasmine\Product\Tests\Application\Property\CommandHandlers;

use RedJasmine\Product\Application\Property\Services\ProductPropertyCommandService;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;
use RedJasmine\Product\Tests\Fixtures\Property\ProductPropertyFaker;

class ProductPropertyCommandHandlersTest extends ApplicationTestCase
{


    public function commandService() : ProductPropertyCommandService
    {
        return app(ProductPropertyCommandService::class)->setOperator($this->user());
    }


    public function repository() : ProductPropertyRepositoryInterface
    {
        return app(ProductPropertyRepositoryInterface::class);
    }


    public function test_can_create() : void
    {
        $command = (new ProductPropertyFaker)->createCommand();

        $productProperty = $this->commandService()->create($command);

        $productProperty = $this->repository()->find($productProperty->id);

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
