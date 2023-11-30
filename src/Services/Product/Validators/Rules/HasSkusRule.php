<?php

namespace RedJasmine\Product\Services\Product\Validators\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;
use RedJasmine\Support\Enums\BoolIntEnum;

class HasSkusRule implements ValidationRule, DataAwareRule, ValidatorAwareRule
{


    protected array     $data;
    protected Validator $validator;

    public function setData(array $data) : void
    {
        $this->data = $data;
    }

    public function setValidator(Validator $validator) : void
    {
        $this->validator = $validator;
    }

    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {

        if (BoolIntEnum::from($value) === BoolIntEnum::YES) {
            $skus = $this->validator->getValue('skus');
            if (blank($skus)) {
                $fail('规格不能为空');
            }
            $saleProps = $this->validator->getValue('info.sale_props');

            if (blank($saleProps)) {
                $fail('销售属性不能为空');
            }
        }
    }


}
