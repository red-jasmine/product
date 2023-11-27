<?php

namespace RedJasmine\Product\Tests\Feature;

use RedJasmine\Product\Enums\Brand\BrandStatusEnum;
use RedJasmine\Product\Services\Brand\BrandService;
use RedJasmine\Product\Tests\TestCase;
use RedJasmine\Support\Services\SystemUser;

class BrandTest extends TestCase
{


    public function service() : BrandService
    {
        $service = new BrandService();
        $service->setOperator(new SystemUser());
        return $service;
    }


    public function testCreate()
    {
        $attributes = [
            'name'    => 'Test',
            'logo'    => 'https://picsum.photos/400/300',
            'status'  => BrandStatusEnum::ENABLE,
            'sort'    => rand(1, 99),
            'extends' => []
        ];


        $brand = $this->service()->create($attributes);
        $this->assertEquals('Test', $brand->name);

        return $brand;
    }


    /**
     * @depends testCreate
     * @return void
     */
    public function testUpdate($brand)
    {

        $attributes = [
            'name'    => 'Test2',
            'logo'    => 'https://picsum.photos/400/300',
            'status'  => BrandStatusEnum::DISABLE,
            'sort'    => rand(1, 99),
            'extends' => []
        ];

        $brandNew = $this->service()->update($brand->id, $attributes);
        $this->assertEquals('Test2', $brandNew->name);
        $this->assertEquals(BrandStatusEnum::DISABLE, $brandNew->status);

        return $brandNew;
    }


    /**
     * @depends testUpdate
     *
     * @param $brand
     *
     * @return void
     */
    public function testModify($brand)
    {

        $attributes = [
            'name'   => 'Test3',
            'status' => BrandStatusEnum::ENABLE,

        ];
        $brandNew   = $this->service()->modify($brand->id, $attributes);
        $this->assertEquals('Test3', $brandNew->name);
        $this->assertEquals(BrandStatusEnum::ENABLE, $brandNew->status);

    }

}
