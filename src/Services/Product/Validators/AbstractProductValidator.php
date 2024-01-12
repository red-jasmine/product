<?php

namespace RedJasmine\Product\Services\Product\Validators;

use Illuminate\Validation\Validator;
use RedJasmine\Support\Foundation\Service\WithUserService;
use RedJasmine\Support\Traits\Models\WithDTO;

abstract class AbstractProductValidator
{
    use WithDTO;

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
