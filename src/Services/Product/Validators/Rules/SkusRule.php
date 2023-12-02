<?php

namespace RedJasmine\Product\Services\Product\Validators\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;
use JsonException;
use RedJasmine\Product\Services\Property\PropertyFormatter;
use RedJasmine\Support\Enums\BoolIntEnum;

class SkusRule implements ValidationRule, DataAwareRule, ValidatorAwareRule
{

    protected array     $data;
    protected Validator $validator;

    protected PropertyFormatter $propertyFormatter;

    /**
     * @param string  $attribute
     * @param mixed   $value
     * @param Closure $fail
     *
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {

        $hasSkus = BoolIntEnum::from($this->data['has_skus'] ?? 0);
        if ($hasSkus === BoolIntEnum::NO) {
            return;
        }

        $saleProps = $this->data['info']['sale_props'] ?? [];

        $this->propertyFormatter = new PropertyFormatter();

        $cross = $this->propertyFormatter->crossJoinToString($saleProps);

        if (count($cross) !== count($value)) {
            $fail('规格数量不一致');
            return;
        }

        $this->salePropsNames = $this->getSalePropsNames();
        foreach ($value as &$sku) {
            $sku['properties'] = $this->propertyFormatter->formatString($sku['properties']);
            if (!in_array($sku['properties'], $cross, true)) {
                $fail('规格不在属性配置中');
                return;
            }
            // 配置下
            $sku['properties_name'] = $this->propertiesName($sku['properties']);
        }

        $this->validator->setValue($attribute, $value);

    }

    public function propertiesName($properties)
    {
        $props = $this->propertyFormatter->toArray($properties);
        $names = [];
        foreach ($props as &$item) {
            $item['name'] = $this->salePropsNames['properties'][$item['pid']];

            foreach ($item['vid'] as $vid) {
                $item['v_names'][] = $this->salePropsNames['values'][$vid];
            }
            $names[] = implode(':', [ $item['name'], implode('、', $item['v_names']) ]);
        }
        return implode(';', $names);
    }

    /**
     * @var array{properties:array<int,string>,values:array<int,string>}
     */
    protected array $salePropsNames = [];

    /**
     * @return array{properties:array<int,string>,values:array<int,string>}
     */
    public function getSalePropsNames() : array
    {

        $saleProps = $this->data['info']['sale_props'] ?? [];

        $properties = [];
        $values     = [];
        foreach ($saleProps as $item) {

            $properties[$item['pid']] = $item['name'];

            foreach ($item['values'] as $value) {
                $values[$value['vid']] = $value['name'];

            }


        }

        return [
            'properties' => $properties,
            'values'     => $values,
        ];

    }

    public function setData(array $data) : void
    {
        $this->data = $data;
    }

    public function setValidator(Validator $validator) : void
    {
        $this->validator = $validator;
    }

}
