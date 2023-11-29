<?php

namespace RedJasmine\Product\Tests\Feature;

use RedJasmine\Product\Enums\Product\ProductStatus;
use RedJasmine\Product\Enums\Product\ProductTypeEnum;
use RedJasmine\Product\Enums\Product\ShippingTypeEnum;
use RedJasmine\Product\Services\Product\ProductService;
use RedJasmine\Product\Services\Property\PropertyFormatter;
use RedJasmine\Support\Helpers\UserObjectBuilder;
use RedJasmine\Support\Services\SystemUser;


class ProductTest extends ProductPropertyTest
{


    public function productService() : ProductService
    {
        $service = new ProductService();
        $service->setOwner(new SystemUser());
        $service->setOperator(new UserObjectBuilder([ 'uid' => 1, 'type' => 'admin', 'nickname' => 'Admin' ]));

        return $service;
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function testCreateBasic()
    {
        $color       = $this->testCreateColorName();
        $colorValues = $this->testCreateColorValues($color);
        $size        = $this->testCreateSizeName();
        $sizeValues  = $this->testCreateSizeValues($size);
        $style       = $this->testCreateStyleName();
        $styleValues = $this->testCreateStyleValues($style);


        $saleProps  = [

        ];
        $colorProps = [
            'pid' => $color->pid,
            'vid' => [],
        ];
        foreach ($colorValues as $value) {
            $colorProps['vid'][] = $value->vid;
        }
        $sizeProps = [
            'pid' => $size->pid,
            'vid' => [],
        ];
        foreach ($sizeValues as $value) {
            $sizeProps['vid'][] = $value->vid;
        }

        $saleProps         = [
            $colorProps, $sizeProps
        ];
        $propertyFormatter = new PropertyFormatter();
        $salePropsString   = $propertyFormatter->toString($saleProps);
        $saleProps         = $propertyFormatter->toArray($salePropsString);
        $cross             = $propertyFormatter->crossJoinToString($saleProps);

        $sku  = [
            'price'      => 56.44,
            'cost_price' => 0,
            'quantity'   => 1,
        ];
        $skus = [];
        foreach ($cross as $properties) {
            $sku['properties'] = $properties;
            $skus[]            = $sku;
        }
        $basicProps = [
            [
                'pid' => $style->pid,
                'vid' => $styleValues[0]->vid
            ]
        ];


        $product = [
            'product_type'  => ProductTypeEnum::GOODS->value,
            'shipping_type' => ShippingTypeEnum::VIRTUAL->value,
            'status'        => ProductStatus::IN_STOCK->value,
            'title'         => '基础商品',
            'price'         => 56.44,
            'cost_price'    => 0,
            'freight_payer' => 0,
            'sub_stock'     => 0,
            'delivery_time' => 0,
            'vip'           => 0,
            'points'        => 0,
            'quantity'      => 1,
            'keywords'      => '潮流 古风',
            'has_skus'      => 1, // 含有多规格
            'info'          => [
                'desc'        => 'desc',
                'basic_props' => $basicProps,
                'sale_props'  => $saleProps,
                'extends'     => [],
            ],
            'skus'          => $skus,
        ];
        $this->productService()->create($product);

    }


}
