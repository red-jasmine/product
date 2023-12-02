<?php

namespace RedJasmine\Product\Services\Product\Validators\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;
use RedJasmine\Support\Enums\BoolIntEnum;

class HasSkusRule extends AbstractRule
{

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
