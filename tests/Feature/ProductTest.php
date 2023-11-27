<?php

namespace RedJasmine\Product\Tests\Feature;

use RedJasmine\Product\Enums\Product\ProductTypeEnum;
use RedJasmine\Product\Enums\Product\ShippingTypeEnum;
use RedJasmine\Product\Services\Product\ProductService;
use RedJasmine\Product\Tests\TestCase;
use RedJasmine\Support\Helpers\UserObjectBuilder;
use RedJasmine\Support\Services\SystemUser;

class ProductTest extends TestCase
{


    public function service() : ProductService
    {
        $service = new ProductService();
        $service->setOwner(new SystemUser());
        $service->setOperator(new UserObjectBuilder([ 'uid' => 1, 'type' => 'admin', 'nickname' => 'Admin' ]));

        return $service;
    }

    public function testCreateBasic()
    {


        $product = [
            'product_type'  => ProductTypeEnum::GOODS,
            'shipping_type' => ShippingTypeEnum::VIRTUAL,
            'name'          => '基础商品',
            'price'         => '56.44',
        ];


        $this->service()->create();

    }

}
