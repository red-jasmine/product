<?php

namespace RedJasmine\Product\Services\Product\Validators\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

class SalePropsRule extends AbstractRule
{

    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        // TODO
        // 验证销售属性 不能在基本属性中


    }


}
