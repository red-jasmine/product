<?php

namespace Property\CommandHandlers;

use RedJasmine\Product\Application\Property\Services\ProductPropertyGroupCommandService;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;
use RedJasmine\Product\Tests\Fixtures\Property\ProductPropertyGroupFaker;

class ProductPropertyGroupCommandHandlersTest extends ApplicationTestCase
{


    protected function commandService() : ProductPropertyGroupCommandService
    {
        return app(ProductPropertyGroupCommandService::class)->setOperator($this->user());
    }


    protected function repository() : ProductPropertyGroupRepositoryInterface
    {
        return app(ProductPropertyGroupRepositoryInterface::class);
    }


    public function test_can_create() : void
    {
        $command = (new ProductPropertyGroupFaker())->createCommand();


        $productProperty = $this->commandService()->create($command);

        $productProperty = $this->repository()->find($productProperty->id);

        $this->assertEquals($command->name, $productProperty->name);

    }


    public function test_can_update() : void
    {
        $command = (new ProductPropertyGroupFaker)->createCommand();

        $productProperty = $this->commandService()->create($command);


        $command = (new ProductPropertyGroupFaker)->updateCommand([ 'id' => $productProperty->id ]);

        $this->commandService()->update($command);

        $productProperty = $this->repository()->find($productProperty->id);

        $this->assertEquals($command->name, $productProperty->name);
        $this->assertEquals($command->sort, $productProperty->sort);


    }

}
