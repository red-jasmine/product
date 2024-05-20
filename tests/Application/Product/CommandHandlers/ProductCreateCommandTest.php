<?php

namespace Product\CommandHandlers;

use RedJasmine\Product\Tests\Application\Product\CommandHandlers\ProductTestCase;
use RedJasmine\Product\Tests\Fixtures\Product\ProductFaker;

class ProductCreateCommandTest extends ProductTestCase
{

    public function test_can_create_product() : void
    {
        $command = (new ProductFaker())->createCommand();

        $this->commandService()->create($command);


    }


    /**
     * 能创建多规格商品
     * 前提条件: 创建好、属性、属性值
     * 步骤：
     *  1、组装 属性值
     *  2、创建商品
     *  3、
     * 预期结果:
     *  1、规格数量一致
     *  2、库存汇总
     * @return void
     */
    public function test_can_create_multiple_spec_product() : void
    {


        $command = (new ProductFaker())->createCommand($this->buildSkusData());

        $command->isMultipleSpec = true;
        $this->commandService()->create($command);


    }

}
