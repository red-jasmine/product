<?php

namespace RedJasmine\Product\Tests\Feature;

use RedJasmine\Product\Enums\Product\ProductStatus;
use RedJasmine\Product\Enums\Product\ProductTypeEnum;
use RedJasmine\Product\Enums\Product\ShippingTypeEnum;
use RedJasmine\Product\Services\Product\ProductService;
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

        $saleProps  = [

        ];
        $colorProps = [
            'pid'    => $color->pid,
            'values' => [],
        ];
        foreach ($colorValues as $value) {
            $colorProps['values'][] = $value->vid;
        }
        $sizeProps = [
            'pid'    => $size->pid,
            'values' => [],
        ];
        foreach ($sizeValues as $value) {
            $sizeProps['values'][] = $value->vid;
        }

        $saleProps       = [
            $colorProps, $sizeProps
        ];
        $salePropsString = [];
        foreach ($saleProps as $value) {
            $salePropsString[] = implode(":", [ $value['pid'], implode(',', $value['values']) ]);
        }
        $salePropsString = implode(';', $salePropsString);


        $product = [
            'product_type'  => ProductTypeEnum::GOODS->value,
            'shipping_type' => ShippingTypeEnum::VIRTUAL->value,
            'status'        => ProductStatus::IN_STOCK->value,
            'title'         => '基础商品',
            'price'         => 56.44,
            'cost_price'    => 0,
            'quantity'      => 1,

            'info' => [
                'desc'       => 'desc',
                'sale_props' => $salePropsString,
                'extends'    => [],
            ]
        ];


        $this->productService()->create($product);


    }

}
