<?php

namespace RedJasmine\Product\Tests\Application\Product\CommandHandlers;

use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\UserCases\Commands\Property;
use RedJasmine\Product\Application\Property\Services\ProductPropertyCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueCommandService;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueCreateCommand;
use RedJasmine\Product\Domain\Product\PropertyFormatter;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyTypeEnum;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;

class ProductTestCase extends ApplicationTestCase
{

    public function commandService() : ProductCommandService
    {
        return app(ProductCommandService::class)->setOperator($this->user());

    }


    protected function createProperties($propertyNames) : array
    {

        $propertyCommandService = app(ProductPropertyCommandService::class);

        $propertyValueCommandService = app(ProductPropertyValueCommandService::class);
        $propertyRepository          = app(ProductPropertyRepositoryInterface::class);
        $propertyValueRepository     = app(ProductPropertyValueRepositoryInterface::class);
        $properties                  = [];
        foreach ($propertyNames as $propertyData) {
            if (!$property = $propertyRepository->findByName($propertyData['name'])) {
                $property = $propertyCommandService->create(ProductPropertyCreateCommand::validateAndCreate($propertyData));
            }
            $values = [];
            // 创建值
            foreach ($propertyData['values'] ?? [] as $valueData) {
                if (!$value = $propertyValueRepository->findByNameInProperty($property->id, $valueData['name'])) {
                    $value = $propertyValueCommandService->create(ProductPropertyValueCreateCommand::validateAndCreate(array_merge([ 'pid' => $property->id ], $valueData)));
                }
                $values[] = $value;
            }

            $properties[] = [
                'property' => $property,
                'values'   => $values,
            ];

        }
        return $properties;

    }


    protected function buildSkusData($skuPropertyNames = [
        '颜色' => [ '白色' => '白金', '黑色' => null, '红色' => null, ],
        '尺码' => [ 'S' => null, 'M' => null, 'L' => null, 'XL' => null, ],
    ]) : array
    {

        $saleProperties = [];
        // 构建属性参数
        $propertyRepository      = app(ProductPropertyRepositoryInterface::class);
        $propertyValueRepository = app(ProductPropertyValueRepositoryInterface::class);
        foreach ($skuPropertyNames as $name => $values) {
            $saleProperty        = [];
            $property            = $propertyRepository->findByName($name);
            $saleProperty['pid'] = $property->id;

            foreach ($values as $valueName => $valueNameAlias) {
                $value                             = $propertyValueRepository->findByNameInProperty($property->id, $valueName);
                $saleProperty['value'][$value->id] = $valueNameAlias;
            }
            // 判断类型
            $saleProperties[] = $saleProperty;
        }

        $saleProps = collect(Property::collect($saleProperties))->toArray();

        $skuProperties = app(PropertyFormatter::class)->crossJoinToString($saleProps);


        $skus = [];

        foreach ($skuProperties as $property) {
            $skus[] = [
                'properties'   => $property,
                'image'        => fake()->imageUrl(),
                'barcode'      => fake()->ean13(),
                'outer_id'     => fake()->numerify('########'),
                'stock'        => fake()->numberBetween(1, 10),
                'price'        => fake()->numberBetween(100, 1000),
                'market_price' => fake()->numberBetween(100, 1000),
                'cost_price'   => fake()->numberBetween(100, 1000),
                'safety_stock' => fake()->numberBetween(100, 1000),
            ];
        }
        return [
            'skus'       => $skus,
            'sale_props' => $saleProps
        ];

    }


    protected function initProperties() : void
    {
        $inits = [
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
                'type'   => PropertyTypeEnum::SELECT->value,
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


        $this->createProperties($inits);

    }


    protected function buildBasicProperties($basePropertyNames = [
        '吊牌价'   => 168,
        '上市时间' => '2024-05-01',
        '年份季节' => '2024年春夏',
        '流行元素' => [ '亮片', '露背' ],

    ]) : array
    {
        $baseProperties = [];
        // 构建属性参数
        $propertyRepository      = app(ProductPropertyRepositoryInterface::class);
        $propertyValueRepository = app(ProductPropertyValueRepositoryInterface::class);
        foreach ($basePropertyNames as $name => $values) {
            $baseProperty        = [];
            $property            = $propertyRepository->findByName($name);
            $baseProperty['pid'] = $property->id;
            switch ($property->type) {
                case PropertyTypeEnum::TEXT:
                case PropertyTypeEnum::DATE:
                    $baseProperty['value'] = (string)$values;
                    break;
                case PropertyTypeEnum::SELECT:
                    $value                 = $propertyValueRepository->findByNameInProperty($property->id, $values);
                    $baseProperty['value'] = $value->id;
                    break;
                case PropertyTypeEnum::MULTIPLE:
                    foreach ($values as $valueName) {
                        $value                   = $propertyValueRepository->findByNameInProperty($property->id, $valueName);
                        $baseProperty['value'][] = $value->id;
                    }

                    break;
            }
            // 判断类型
            $baseProperties[] = $baseProperty;
        }

        $result = Property::collect($baseProperties);

        return collect($result)->toArray();
    }

}
