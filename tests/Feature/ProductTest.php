<?php

namespace RedJasmine\Product\Tests\Feature;

use RedJasmine\Product\Enums\Product\FreightPayerEnum;
use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Product\Enums\Product\ProductTypeEnum;
use RedJasmine\Product\Enums\Product\ShippingTypeEnum;
use RedJasmine\Product\Enums\Product\SubStockTypeEnum;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Services\Product\ProductService;
use RedJasmine\Product\Services\Property\PropertyFormatter;
use RedJasmine\Support\Helpers\User\SystemUser;
use RedJasmine\Support\Helpers\User\UserObject;


class ProductTest extends ProductPropertyTest
{


    public function productService() : ProductService
    {
        $service = new ProductService();
        $service->setOwner(new SystemUser());
        $service->setOperator(new UserObject([ 'id' => 1, 'type' => 'admin', 'nickname' => 'Admin' ]));

        return $service;
    }


    /**
     * @return void
     * @throws \Exception
     */
    public function ttestCreateBasicProduct()
    {

        $style       = $this->testCreateStyleName();
        $styleValues = $this->testCreateStyleValues($style);
        $basicProps  = [
            [
                'pid' => $style->pid,
                'vid' => $styleValues[0]->vid
            ]
        ];
        $product     = [
            'product_type'  => ProductTypeEnum::GOODS->value,
            'shipping_type' => ShippingTypeEnum::VIRTUAL->value,
            'status'        => ProductStatusEnum::IN_STOCK->value,
            'title'         => '基础商品',
            'price'         => 56.44,
            'cost_price'    => 0,
            'freight_payer' => FreightPayerEnum::DEFAULT->value,
            'sub_stock'     => SubStockTypeEnum::DEFAULT->value,
            'delivery_time' => 0,
            'vip'           => 0,
            'points'        => 0,
            'stock'      => 1,
            'keywords'      => '潮流 古风',
            'is_multiple_spec'      => 0, // 含有多规格
            'info'          => [
                'desc'        => 'desc',
                'basic_props' => $basicProps,
                'sale_props'  => [],
                'extends'     => [],
            ],
            'skus'          => [],
        ];
        //$product     = $this->productService()->create($product);


    }


    /**
     * @return void
     * @throws \Throwable
     */
    public function testCreateSkusProduct()
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
            'stock'   => 1,
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


        $data         = [
            'product_type'  => ProductTypeEnum::GOODS->value,
            'shipping_type' => ShippingTypeEnum::VIRTUAL->value,
            'status'        => ProductStatusEnum::IN_STOCK->value,
            'title'         => '基础商品',
            'price'         => 56.44,
            'cost_price'    => 0,
            'freight_payer' => FreightPayerEnum::DEFAULT->value,
            'sub_stock'     => SubStockTypeEnum::DEFAULT->value,
            'delivery_time' => 0,
            'vip'           => 0,
            'points'        => 0,
            'stock'      => 1,
            'keywords'      => '潮流 古风',
            'is_multiple_spec'      => 1, // 含有多规格
            'info'          => [
                'desc'        => 'desc',
                'basic_props' => $basicProps,
                'sale_props'  => $saleProps,
                'extends'     => [],
            ],
            'skus'          => $skus,
        ];
        $productModel = $this->productService()->create($data);

        $this->assertEquals('基础商品', $productModel->title);
        return $productModel;
    }


    /**
     * @depends testCreateSkusProduct
     *
     * @param Product $product
     *
     * @return void
     * @throws \Exception
     */
    public function testUpdateProduct(Product $product)
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
            'price'      => rand(1, 100),
            'cost_price' => 0,
            'stock'   => rand(1, 100),
        ];
        $skus = [];
        foreach ($cross as $properties) {
            $sku['properties'] = $properties;
            $sku['price']      = rand(1, 100);
            $sku['stock']   = rand(1, 100);
            $skus[]            = $sku;
        }
        $basicProps = [
            [
                'pid' => $style->pid,
                'vid' => $styleValues[0]->vid
            ]
        ];


        $data = [
            'product_type'  => ProductTypeEnum::GOODS->value,
            'shipping_type' => ShippingTypeEnum::VIRTUAL->value,
            'status'        => ProductStatusEnum::OUT_OF_STOCK,
            'title'         => '基础商品-' . rand(1, 9999),
            'price'         => 56.44,
            'cost_price'    => 0,
            'freight_payer' => FreightPayerEnum::DEFAULT->value,
            'sub_stock'     => SubStockTypeEnum::DEFAULT->value,
            'delivery_time' => 0,
            'vip'           => 0,
            'points'        => 0,
            'stock'      => 1,
            'keywords'      => '潮流 古风 2',
            'is_multiple_spec'      => 1, // 含有多规格
            'info'          => [
                'desc'        => 'desc update 2',
                'basic_props' => $basicProps,
                'sale_props'  => $saleProps,
                'extends'     => [],
            ],
            'skus'          => $skus,
        ];


        $productModel = $this->productService()->update($product->id, $data);

        return $productModel;
    }


    /**
     * @depends testUpdateProduct
     *
     * @param Product $product
     *
     * @return Product
     * @throws \Exception|\Throwable
     */
    public function testUpdateAddSaleProps(Product $product)
    {

        $color        = $this->testCreateColorName();
        $colorValues  = $this->testCreateColorValues($color);
        $size         = $this->testCreateSizeName();
        $sizeValues   = $this->testCreateSizeValues($size);
        $style        = $this->testCreateStyleName();
        $styleValues  = $this->testCreateStyleValues($style);
        $length       = $this->testCreateLengthName();
        $lengthValues = $this->testCreateStyleValues($length);

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

        $lengthProps = [
            'pid' => $length->pid,
            'vid' => [],
        ];
        foreach ($lengthValues as $value) {
            $lengthProps['vid'][] = $value->vid;
        }


        $saleProps         = [
            $colorProps, $sizeProps, $lengthProps
        ];
        $propertyFormatter = new PropertyFormatter();
        $salePropsString   = $propertyFormatter->toString($saleProps);
        $saleProps         = $propertyFormatter->toArray($salePropsString);
        $cross             = $propertyFormatter->crossJoinToString($saleProps);

        $sku  = [
            'price'      => rand(1, 100),
            'cost_price' => 0,
            'stock'   => rand(1, 100),
        ];
        $skus = [];
        foreach ($cross as $properties) {
            $sku['properties'] = $properties;
            $sku['price']      = rand(1, 100);
            $sku['stock']   = rand(1, 100);
            $skus[]            = $sku;
        }

        $basicProps = [
            [
                'pid' => $style->pid,
                'vid' => $styleValues[0]->vid
            ]
        ];


        $data = [
            'product_type'  => ProductTypeEnum::GOODS->value,
            'shipping_type' => ShippingTypeEnum::VIRTUAL->value,
            'status'        => ProductStatusEnum::OUT_OF_STOCK,
            'title'         => '基础商品-' . rand(1, 9999),
            'price'         => 56.44,
            'cost_price'    => 0,
            'freight_payer' => FreightPayerEnum::DEFAULT->value,
            'sub_stock'     => SubStockTypeEnum::DEFAULT->value,
            'delivery_time' => 0,
            'vip'           => 0,
            'points'        => 0,
            'stock'      => 1,
            'keywords'      => '潮流 古风 2',
            'is_multiple_spec'      => 1, // 含有多规格
            'info'          => [
                'desc'        => 'desc update 2',
                'basic_props' => $basicProps,
                'sale_props'  => $saleProps,
                'extends'     => [],
            ],
            'skus'          => $skus,
        ];

        $productModel = $this->productService()->update($product->id, $data);

        return $productModel;
    }


    /**
     * @depends  testUpdateAddSaleProps
     *
     * @param Product $product
     *
     * @return Product
     * @throws \Exception
     */
    public function testUpdateRemoveSaleProps(Product $product)
    {

        $product = $this->testUpdateProduct($product);


        return $product;
    }


    /**
     * @depends  testUpdateRemoveSaleProps
     *
     * @param Product $product
     *
     * @return void
     * @throws \Throwable
     */
    public function testModifyProduct(Product $product)
    {


        $data    = [
            'title'  => '修改价格',
            'price'  => '9988',
            'status' => ProductStatusEnum::OUT_OF_STOCK

        ];
        $product = $this->productService()->modify($product->id, $data);

        $this->assertEquals(9988, $product->price);

        $data    = [
            'title'    => '关闭多规格',
            'is_multiple_spec' => 0,
        ];
        $product = $this->productService()->modify($product->id, $data);


        $this->assertCount(0, $product->skus);


    }


}
