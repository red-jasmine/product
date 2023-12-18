<?php

namespace RedJasmine\Product\Services\Product\Validators;

use Illuminate\Validation\Validator;
use RedJasmine\Support\Traits\Services\WithUserService;

abstract class AbstractProductValidator
{
    use WithUserService;

    /**
     * 验证规则
     * @return array
     */
    abstract public function rules() : array;


    public function messages() : array
    {
        return [];
    }

    public function attributes() : array
    {
        return [];
    }


    public function withValidator(Validator $validator) : void
    {
    }


}
