<?php

namespace RedJasmine\Product\Services\Category\Validators\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

class CategoryParentRule implements ValidationRule, DataAwareRule, ValidatorAwareRule
{
    public function __construct(protected string $table)
    {
    }


    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        if ((int)$value === 0) {
            return;
        }
        // TODO
        // 1、parent_id 不能为自己的子集
        // 查询当前ID 下的所有 自己

    }


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

}
