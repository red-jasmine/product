<?php

namespace RedJasmine\Product\Services\Product\Validators\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

class SalePropsRule implements ValidationRule, DataAwareRule, ValidatorAwareRule
{

    protected array     $data;
    protected Validator $validator;

    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        // TODO 销售属性验证
        // 基础验证 数据库验证
        // 重新排序

        // 对SKU 数量验证
        //  属性 、sku
        dd($attribute, $value);
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
