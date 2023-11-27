<?php

namespace RedJasmine\Product\Services\Product\Validators;

use Illuminate\Validation\Validator;
use RedJasmine\Support\Traits\WithUserService;

abstract class AbstractProductValidator
{
    use WithUserService;


    /**
     * 数据验证
     *
     * @param array $data
     *
     * @return mixed
     */
    abstract public function validator(array $data) : Validator;
}
