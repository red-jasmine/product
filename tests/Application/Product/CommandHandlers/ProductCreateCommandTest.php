<?php

namespace Product\CommandHandlers;

use Illuminate\Support\Arr;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueCommandService;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueCreateCommand;
use RedJasmine\Product\Domain\Property\PropertyFormatter;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;
use RedJasmine\Product\Tests\Fixtures\Product\ProductFaker;

class ProductCreateCommandTest extends ApplicationTestCase
{

    public function commandService() : ProductCommandService
    {
        return app(ProductCommandService::class)->setOperator($this->user());

    }

    public function test_can_create_product() : void
    {
        $command = (new ProductFaker())->createCommand();

        $this->commandService()->create($command);


    }

    protected function create_properties() : array
    {

        $propertyCommandService = app(ProductPropertyCommandService::class);
        $colourProperty         = $propertyCommandService->create(ProductPropertyCreateCommand::from([ 'name' => '颜色' ]));
        $sizeProperty           = $propertyCommandService->create(ProductPropertyCreateCommand::from([ 'name' => '尺码' ]));

        $propertyValueCommandService = app(ProductPropertyValueCommandService::class);
        $colours                     = [];
        $colours[]                   = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::from([ 'pid' => $colourProperty->id, 'name' => '白色' ]));
        $colours[]                   = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::from([ 'pid' => $colourProperty->id, 'name' => '黑色' ]));
        $colours[]                   = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::from([ 'pid' => $colourProperty->id, 'name' => '红色' ]));
        $colours[]                   = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::from([ 'pid' => $colourProperty->id, 'name' => '绿色' ]));
        $colours[]                   = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::from([ 'pid' => $colourProperty->id, 'name' => '蓝色' ]));

        $sizes   = [];
        $sizes[] = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::from([ 'pid' => $sizeProperty->id, 'name' => 'S' ]));
        $sizes[] = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::from([ 'pid' => $sizeProperty->id, 'name' => 'M' ]));
        $sizes[] = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::from([ 'pid' => $sizeProperty->id, 'name' => 'L' ]));
        $sizes[] = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::from([ 'pid' => $sizeProperty->id, 'name' => 'XL' ]));
        $sizes[] = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::from([ 'pid' => $sizeProperty->id, 'name' => '2XL' ]));
        $sizes[] = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::from([ 'pid' => $sizeProperty->id, 'name' => '3XL' ]));
        $sizes[] = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::from([ 'pid' => $sizeProperty->id, 'name' => '4XL' ]));


        return [
            [
                'property' => $colourProperty,
                'values'   => $colours,
            ],
            [
                'property' => $sizeProperty,
                'values'   => $sizes,
            ],
        ];
    }


    protected function buildSkusData() : array
    {
        $properties = $this->create_properties();


        $saleProps = [];
        foreach ($properties as $item) {
            $saleProps[] = [
                'pid' => $item['property']->id,
                'vid' => collect($item['values'])->pluck('id')->toArray(),
            ];
        }


        $skuProperties = app(PropertyFormatter::class)->crossJoinToString($saleProps);


        $skus = [];
        foreach ($skuProperties as $property) {
            $skus[] = [
                'properties'   => $property,
                'stock'        => fake()->numberBetween(10, 100),
                'image'        => fake()->imageUrl(),
                'barcode'      => fake()->ean13(),
                'price'        => fake()->numberBetween(100, 1000),
                'market_price' => fake()->numberBetween(100, 1000),
                'cost_price'   => fake()->numberBetween(100, 1000),
                'outer_id'     => fake()->numerify('########'),
                'safety_stock' => fake()->numberBetween(100, 1000),

            ];

        }


        return [
            'skus'       => $skus,
            'sale_props' => $saleProps
        ];

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


        $command                 = (new ProductFaker())->createCommand($this->buildSkusData());

        $command->isMultipleSpec = true;
        $this->commandService()->create($command);


    }

}
