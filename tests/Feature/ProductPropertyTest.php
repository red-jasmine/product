<?php

namespace RedJasmine\Product\Tests\Feature;

use RedJasmine\Product\Models\ProductProperty;
use RedJasmine\Product\Models\ProductPropertyValue;
use RedJasmine\Product\Services\Property\PropertyService;
use RedJasmine\Product\Tests\TestCase;
use RedJasmine\Support\Services\SystemUser;

class ProductPropertyTest extends TestCase
{

    public function service()
    {

        $propertyService = new PropertyService();
        $propertyService->setOperator(new SystemUser());
        return $propertyService;
    }


    public function testCreateColorName()
    {
        $propertyService = $this->service();

        $name  = '颜色';
        $color = $propertyService->createName($name);

        $this->assertEquals($name, $color->name);

        $propertyData = [
            'status' => 1
        ];
        $property     = $propertyService->createName($name, $propertyData);


        $count = ProductProperty::where('name', $name)->count();

        $this->assertEquals($count, 1);

        return $color;

    }

    public function testCreateSizeName()
    {
        $propertyService = $this->service();

        $name = '尺码';
        $size = $propertyService->createName($name);

        $this->assertEquals($name, $size->name);

        return $size;

    }


    /**
     * @depends testCreateColorName
     *
     * @param ProductProperty $property
     *
     * @return array
     * @throws \Exception
     */
    public function testCreateColorValues(ProductProperty $property) : array
    {

        $service = $this->service();

        $vName         = '白色';
        $propertyValue = $service->createValue($property->pid, $vName);

        $this->assertEquals($property->pid, $propertyValue->pid);
        $this->assertEquals($vName, $propertyValue->name);

        $propertyValue = $service->createValue($property->pid, $vName);

        $count = ProductPropertyValue::where('pid', $property->pid)->where('name', $vName)->count();

        $this->assertEquals(1, $count);

        $vName2         = '黑色';
        $propertyValue2 = $service->createValue($property->pid, $vName2);

        $vName3         = '红色';
        $propertyValue3 = $service->createValue($property->pid, $vName3);


        return [
            $propertyValue,
            $propertyValue2,
            $propertyValue3
        ];
    }


    /**
     * @depends testCreateSizeName
     *
     * @param ProductProperty $property
     *
     * @return array
     * @throws \Exception
     */
    public function testCreateSizeValues(ProductProperty $property) : array
    {

        $service = $this->service();
        $data    = [
            'group_name' => '衣服'
        ];

        $vName         = 'S';
        $propertyValue = $service->createValue($property->pid, $vName, $data);

        $this->assertEquals($property->pid, $propertyValue->pid);
        $this->assertEquals($vName, $propertyValue->name);

        $propertyValue = $service->createValue($property->pid, $vName, $data);

        $count = ProductPropertyValue::where('pid', $property->pid)->where('name', $vName)->count();

        $this->assertEquals(1, $count);

        $vName2         = 'M';
        $propertyValue2 = $service->createValue($property->pid, $vName2, $data);

        $vName3         = 'L';
        $propertyValue3 = $service->createValue($property->pid, $vName3, $data);


        return [
            $propertyValue,
            $propertyValue2,
            $propertyValue3
        ];
    }


    public function testCreateStyleName()
    {
        $propertyService = $this->service();

        $name = '风格';
        $size = $propertyService->createName($name);

        $this->assertEquals($name, $size->name);

        return $size;

    }


    /**
     * @depends testCreateStyleName
     *
     * @param ProductProperty $property
     *
     * @return array
     * @throws \Exception
     */
    public function testCreateStyleValues(ProductProperty $property) : array
    {

        $service = $this->service();

        $vName         = '古风';
        $propertyValue = $service->createValue($property->pid, $vName);

        $this->assertEquals($property->pid, $propertyValue->pid);
        $this->assertEquals($vName, $propertyValue->name);

        $propertyValue = $service->createValue($property->pid, $vName);

        $count = ProductPropertyValue::where('pid', $property->pid)->where('name', $vName)->count();

        $this->assertEquals(1, $count);

        $vName2         = '潮流';
        $propertyValue2 = $service->createValue($property->pid, $vName2);

        $vName3         = '嘻哈';
        $propertyValue3 = $service->createValue($property->pid, $vName3);


        return [
            $propertyValue,
            $propertyValue2,
            $propertyValue3
        ];
    }


}
