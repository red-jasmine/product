<?php

namespace RedJasmine\Product\Tests\Feature;

use RedJasmine\Product\Enums\Category\CategoryStatusEnum;
use RedJasmine\Product\Services\Category\ProductCategoryService;
use RedJasmine\Product\Tests\TestCase;
use RedJasmine\Support\Services\SystemUser;

class ProductCategoryTest extends TestCase
{


    protected function service()
    {
        $service = \app(ProductCategoryService::class);
        $service->setOperator(new SystemUser());

        return $service;
    }

    /**
     *
     * @return void
     * @throws \Exception
     */
    public function testCreate()
    {

        $attributes      = [
            'parent_id'  => 0,
            'name'       => '一级类目',
            'group_name' => '',
            'sort'       => 1,
            'is_leaf'    => 0,
            'status'     => CategoryStatusEnum::ENABLE,
            'extends'    => [],
        ];
        $productCategory = $this->service()->create($attributes);
        $this->assertEquals('一级类目', $productCategory->name);
        return $productCategory;


    }


    /**
     *
     * @depends testCreate
     *
     * @param $productCategory
     *
     * @return void
     * @throws \Exception
     */
    public function testCreate2($productCategory)
    {

        $attributes2 = [
            'parent_id'  => $productCategory->id,
            'name'       => '二级类目',
            'group_name' => '',
            'sort'       => 1,
            'is_leaf'    => 0,
            'status'     => CategoryStatusEnum::ENABLE,
            'extends'    => [],
        ];

        $productCategory2 = $this->service()->create($attributes2);

        $this->assertEquals($productCategory->id, $productCategory2->parent_id);
        $this->assertEquals('二级类目', $productCategory2->name);

        return $productCategory2;
    }


    /**
     * @depends testCreate2
     * @return void
     */
    public function testUpdate($productCategory)
    {


        $attributes = [
            'parent_id'  => 0,
            'name'       => '修改分类',
            'group_name' => '',
            'sort'       => rand(1, 199),
            'is_leaf'    => 0,
            'status'     => CategoryStatusEnum::ENABLE,
            'extends'    => [],
        ];
        $this->service()->update($productCategory->id, $attributes);
        $productCategory = $this->service()->find($productCategory->id);

        $this->assertEquals($attributes['name'], $productCategory->name);
        $this->assertEquals($attributes['parent_id'], $productCategory->parent_id);
        $this->assertEquals($attributes['group_name'], $productCategory->group_name);
        $this->assertEquals($attributes['sort'], $productCategory->sort);
        $this->assertEquals($attributes['is_leaf'], $productCategory->is_leaf);
        $this->assertEquals($attributes['status'], $productCategory->status);
        $this->assertEquals($attributes['extends'], $productCategory->extends);
    }


    /**
     * @depends testCreate2
     *
     * @param $productCategory
     *
     * @return void
     */
    public function testModify($productCategory)
    {


        $attributes = [
            'parent_id'  => 0,
            'name'       => '修改分类',
            'group_name' => '',
            'sort'       => rand(1, 199),
            'is_leaf'    => 0,
            'status'     => CategoryStatusEnum::ENABLE,
            'extends'    => [
                'ss' => 'aa'
            ],
        ];
        $this->service()->modify($productCategory->id, $attributes);
        $productCategory = $this->service()->find($productCategory->id);
        $this->assertEquals($attributes['name'], $productCategory->name);
        $this->assertEquals($attributes['parent_id'], $productCategory->parent_id);
        $this->assertEquals($attributes['group_name'], $productCategory->group_name);
        $this->assertEquals($attributes['sort'], $productCategory->sort);
        $this->assertEquals($attributes['is_leaf'], $productCategory->is_leaf);
        $this->assertEquals($attributes['status'], $productCategory->status);
        $this->assertEquals($attributes['extends'], $productCategory->extends);
    }

}
