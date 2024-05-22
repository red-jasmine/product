<?php

namespace RedJasmine\Product\Tests\Application\Product\CommandHandlers;

use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueCommandService;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueCreateCommand;
use RedJasmine\Product\Domain\Property\PropertyFormatter;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;
use RedJasmine\Product\Tests\Fixtures\Product\ProductFaker;

class ProductTestCase extends ApplicationTestCase
{

    public function commandService() : ProductCommandService
    {
        return app(ProductCommandService::class)->setOperator($this->user());

    }


    protected function create_properties($propertyNames) : array
    {

        $propertyCommandService      = app(ProductPropertyCommandService::class);
        $propertyValueCommandService = app(ProductPropertyValueCommandService::class);
        $properties                  = [];
        foreach ($propertyNames as $name => $valueNames) {
            $property = $propertyCommandService->create(ProductPropertyCreateCommand::from([ 'name' => $name ]));
            $values   = [];
            // 创建值
            foreach ($valueNames as $valueName) {
                $values[] = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::from([ 'pid' => $property->id, 'name' => $valueName ]));
            }

            $properties[] = [
                'property' => $property,
                'values'   => $values,
            ];

        }
        return $properties;

    }


    protected function buildSkusData($propertyNames = [
        '颜色' => [ '白色', '黑色', '红色', '蓝色', '绿色' ],
        '尺码' => [ 'S', 'M', 'L', 'XL', '2XL', '3XL', '4XL' ],
    ]) : array
    {
        $properties = $this->create_properties($propertyNames);

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
                'stock'        => fake()->numberBetween(1, 10),
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


    protected function buildBaseProperties($propertyNames = [
        '年份季节' => '2024年春夏', // 单选
        '款式'     => '其他',// 单选
        '材质成分' => [ '亚麻' => 8, '兔毛' => 5, ],// 值参数
        '重量'     => 54, // 输入类型
        '吊牌价'   => 156, // 输入
        '流行元素' => [ '流星元素', '亮片' ],// 多选
    ])
    {

    }

}
