<?php

namespace RedJasmine\Product\Services\Product\Validators\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Product\Services\Property\PropertyFormatter;
use RedJasmine\Product\Services\Property\PropertyService;

class PropsRule implements ValidationRule, DataAwareRule, ValidatorAwareRule
{

    protected array     $data;
    protected Validator $validator;

    /**
     * 属性验证
     *
     * @param string  $attribute
     * @param mixed   $value
     * @param Closure $fail
     *
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        $propertyFormatter = new PropertyFormatter();

        $value = $propertyFormatter->formatArray($value);

        try {
            $value = (new PropertyService())->validateProps($value);
        } catch (ProductPropertyException $productPropertyException) {
            $fail($productPropertyException->getMessage());
            return;
        }
        $this->validator->setValue($attribute, $value);

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
