<?php

namespace RedJasmine\Product\Application\Property\Services;

use Illuminate\Support\Collection;
use RedJasmine\Product\Application\Product\UserCases\Commands\Sku;
use RedJasmine\Product\Domain\Product\Models\ValueObjects\Property;
use RedJasmine\Product\Domain\Product\Models\ValueObjects\PropValue;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyTypeEnum;
use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyReadRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueReadRepositoryInterface;

/**
 * 属性验证服务
 */
class PropertyValidateService
{
    public function __construct(
        protected ProductPropertyReadRepositoryInterface      $propertyReadRepository,
        protected ProductPropertyValueReadRepositoryInterface $valueReadRepository,
    )
    {

    }


    /**
     * 基础属性验证
     *
     * @param array $props
     *
     * @return Collection
     */
    public function basicProps(array $props = []) : Collection
    {
        $pid        = collect($props)->pluck('pid')->unique()->toArray();
        $properties = collect($this->propertyReadRepository->findByIds($pid))->keyBy('id');
        $saleProps  = collect();
        foreach ($props as $prop) {
            $saleProp = new Property();
            /**
             * @var $property ProductProperty
             */
            $property         = $properties[$prop['pid']];
            $saleProp->pid    = $property->id;
            $saleProp->name   = $property->name;
            $saleProp->values = collect();

            $values = $prop['values'] ?? [];

            switch ($property->type) {
                case PropertyTypeEnum::TEXT:
                case PropertyTypeEnum::DATE:

                    $salePropValue        = new PropValue();
                    $salePropValue->vid   = 0;
                    $salePropValue->name  = (string)($values[0]['name'] ?? '');
                    $salePropValue->alias = (string)($values[0]['alias'] ?? '');
                    $saleProp->values->add($salePropValue);
                    break;
                case PropertyTypeEnum::SELECT:
                case PropertyTypeEnum::MULTIPLE:

                    $propValues       = $this->valueReadRepository->findByIdsInProperty($saleProp->pid, collect($values)->pluck('vid')->toArray())->keyBy('id');
                    $saleProp->values = collect();
                    foreach ($values as $value) {
                        $vid                  = $value['vid'];
                        $alias                = $value['alias'] ?? '';
                        $salePropValue        = new PropValue();
                        $salePropValue->vid   = $vid;
                        $salePropValue->name  = $propValues[$salePropValue->vid]->name;
                        $salePropValue->alias = $alias;
                        $saleProp->values->add($salePropValue);
                    }

                    break;
            }
            $saleProps->push($saleProp);

        }


        return $saleProps;


    }


    /**
     * 验证销售属性
     *
     * @param array $props
     *
     * @return Collection<Property>
     */
    public function saleProps(array $props = []) : Collection
    {

        $pid        = collect($props)->pluck('pid')->unique()->toArray();
        $properties = collect($this->propertyReadRepository->findByIds($pid))->keyBy('id');
        $saleProps  = collect();
        foreach ($props as $prop) {
            $saleProp       = new Property();
            $property       = $properties[$prop['pid']];
            $saleProp->pid  = $property->id;
            $saleProp->name = $property->name;
            $values         = $prop['values'] ?? [];

            $propValues = $this->valueReadRepository->findByIdsInProperty($saleProp->pid, collect($values)->pluck('vid')->toArray())->keyBy('id');

            $saleProp->values = collect();
            foreach ($values as $value) {
                $vid                  = $value['vid'];
                $alias                = $value['alias'] ?? '';
                $salePropValue        = new PropValue();
                $salePropValue->vid   = $vid;
                $salePropValue->name  = $propValues[$salePropValue->vid]->name;
                $salePropValue->alias = $alias;
                $saleProp->values->add($salePropValue);
            }
            $saleProps[] = $saleProp;
        }
        return $saleProps;
    }


    /**
     * @param Collection<Property> $saleProps
     * @param Collection<Sku>      $skus
     *
     * @return Collection
     */
    public function validateSkus(Collection $saleProps, Collection $skus) : Collection
    {
        dd($saleProps);

    }
}
