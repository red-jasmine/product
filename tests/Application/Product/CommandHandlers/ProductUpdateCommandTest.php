<?php

namespace RedJasmine\Product\Tests\Application\Product\CommandHandlers;

use RedJasmine\Product\Tests\Fixtures\Product\ProductFaker;

class ProductUpdateCommandTest extends ProductTestCase
{


    public function test_can_update_product() : void
    {
        $command = (new ProductFaker())->createCommand($this->buildSkusData([
                                                                                '颜色' => [ '白色', '黑色', ],
                                                                                '尺码' => [ 'L', ],
                                                                            ]));


        $command->isMultipleSpec = true;

        $product = $this->commandService()->create($command);


        $updateCommand                 = (new ProductFaker())->updateCommand(array_merge($this->buildSkusData(
            [
                '颜色' => [ '白色', '红色', '蓝色', '绿色', ],
                '尺码' => [ 'L', 'XL', '2XL', ],
            ]
        ),                                                                               [ 'id' => $product->id ]));
        $updateCommand->isMultipleSpec = true;

        $this->commandService()->update($updateCommand);


    }

}
