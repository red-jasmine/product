<?php

namespace RedJasmine\Product\Tests\Application\Product\CommandHandlers;

use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueCommandService;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueCreateCommand;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyTypeEnum;
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
            $property = $propertyCommandService->create(ProductPropertyCreateCommand::validateAndCreate([ 'name' => $name ]));
            $values   = [];
            // 创建值
            foreach ($valueNames as $valueName) {
                $values[] = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::validateAndCreate([ 'pid' => $property->id, 'name' => $valueName ]));
            }

            $properties[] = [
                'property' => $property,
                'values'   => $values,
            ];

        }
        return $properties;

    }


    protected function buildSkusData($propertyNames = [
        '颜色' => [ '白色', '黑色', '红色', ],
        '尺码' => [ 'S', 'M', 'L', 'XL', ],
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


    protected function defaultProperties() : array
    {
        return [
            [
                'name'   => '颜色',
                'type'   => PropertyTypeEnum::SELECT->value,
                'values' => [
                    [ 'name' => '白色' ],
                    [ 'name' => '黑色' ],
                    [ 'name' => '红色' ],
                    [ 'name' => '蓝色' ],
                    [ 'name' => '绿色' ],
                ],
            ],
            [
                'name'   => '尺码',
                'type'   => PropertyTypeEnum::SELECT,
                'values' => [
                    [ 'name' => 'S', ],
                    [ 'name' => 'M' ],
                    [ 'name' => 'L' ],
                    [ 'name' => 'XL' ],
                    [ 'name' => 'XXL' ],
                ],
            ],
            [
                'name'   => '吊牌价',
                'unit'   => '元',
                'type'   => PropertyTypeEnum::TEXT->value,
                'values' => [],
            ],
            [
                'name'   => '款式',
                'type'   => PropertyTypeEnum::SELECT->value,
                'values' => [
                    [ 'name' => '吊带' ],
                    [ 'name' => '披肩' ],
                    [ 'name' => '其他' ],
                ],
            ],
            [
                'name'   => '上市时间',
                'type'   => PropertyTypeEnum::DATE->value,
                'values' => [

                ],
            ],
            [
                'name'   => '年份季节',
                'type'   => PropertyTypeEnum::TEXT->value,
                'values' => [
                    [ 'name' => '2024年春夏' ],
                    [ 'name' => '2024年秋冬' ],
                ],
            ],
            [
                'name'   => '流行元素',
                'type'   => PropertyTypeEnum::MULTIPLE->value,
                'values' => [
                    [ 'name' => '流星元素' ],
                    [ 'name' => '亮片' ],
                    [ 'name' => '露背' ],
                    [ 'name' => '做旧' ],
                ],
            ],
        ];
    }


    protected function createProperties(array $properties = null) : array
    {

        if ($properties === null) {
            $properties = $this->defaultProperties();
        }


        $propertyCommandService      = app(ProductPropertyCommandService::class);
        $propertyValueCommandService = app(ProductPropertyValueCommandService::class);
        foreach ($properties as $propertyData) {
            $property = $propertyCommandService->create(ProductPropertyCreateCommand::validateAndCreate($propertyData));
            $values   = [];
            // 创建值
            foreach ($propertyData['values'] ?? [] as $valueData) {
                $values[] = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::validateAndCreate([ 'pid' => $property->id, ...$valueData ]));
            }

            $properties[] = [
                'property' => $property,
                'values'   => $values,
            ];

        }

        return $properties;


    }


    protected function buildBaseProperties($propertyNames = [
        '吊牌价'   => 168,
        '上市时间' => '2024-05-01',
        '年份季节' => [ '2024年春夏' ],
        '流行元素' => [ '亮片', '露背' ],

    ])
    {

    }

}
