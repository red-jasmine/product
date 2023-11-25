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


    public function testCreateName()
    {
        $propertyService = $this->service();

        $name     = '颜色';
        $property = $propertyService->createName($name);

        $this->assertEquals($name, $property->name);

        $propertyData = [
            'status' => 1
        ];
        $property     = $propertyService->createName($name, $propertyData);


        $count = ProductProperty::where('name', $name)->count();

        $this->assertEquals($count, 1);


        return $property;

    }


    /**
     * @depends testCreateName
     *
     * @param ProductProperty $property
     *
     * @return void
     * @throws \Exception
     */
    public function testCreateValue(ProductProperty $property)
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


        return $propertyValue;
    }


    /**
     * @depends testCreateValue
     *
     * @param ProductPropertyValue $propertyValue
     *
     * @return void
     */
    public function testValues(ProductPropertyValue $propertyValue)
    {

        $service = $this->service();

        $values = $service->values($propertyValue->pid);


        dd($values);

    }

}
