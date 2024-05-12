<?php

namespace Category\CommandHandlers;

use RedJasmine\Product\Application\Category\Services\ProductCategoryCommandService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryUpdateCommand;
use RedJasmine\Product\Domain\Category\Enums\CategoryStatusEnum;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;

class ProductCategoryUpdateCommandHandlerTest extends ApplicationTestCase
{


    protected function productCategoryRepository() : ProductCategoryRepositoryInterface
    {
        return app(ProductCategoryRepositoryInterface::class);
    }

    protected function productCategoryCommandService() : ProductCategoryCommandService
    {
        return app(ProductCategoryCommandService::class)->setOperator($this->user());
    }


    public function test_can_update_product_category() : void
    {

        $command = ProductCategoryCreateCommand::from([
                                                          'name'       => fake()->name,
                                                          'parent_id'  => 0,
                                                          'status'     => CategoryStatusEnum::ENABLE->value,
                                                          'sort'       => fake()->numberBetween(0, 1000),
                                                          'is_leaf'    => false,
                                                          'is_show'    => false,
                                                          'group_name' => fake()->name,
                                                          'image'      => fake()->imageUrl,
                                                          'extends'    => [],
                                                      ]);


        $id = $this->productCategoryCommandService()->create($command);

        $updateCommand = ProductCategoryUpdateCommand::from([
                                                                'id'         => $id,
                                                                'parent_id'  => 0,
                                                                'name'       => fake()->name,
                                                                'status'     => CategoryStatusEnum::ENABLE->value,
                                                                'sort'       => fake()->numberBetween(0, 1000),
                                                                'is_leaf'    => false,
                                                                'is_show'    => false,
                                                                'group_name' => fake()->name,
                                                                'image'      => fake()->imageUrl,
                                                                'extends'    => [],
                                                            ]);


        $this->productCategoryCommandService()->update($updateCommand);


        $productCategory = $this->productCategoryRepository()->find($id);

        $this->assertEquals($updateCommand->name, $productCategory->name);
        $this->assertEquals($updateCommand->parentId, $productCategory->parent_id);


    }

}
