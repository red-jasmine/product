<?php

namespace RedJasmine\Product\Services\Product\Validators\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

abstract class AbstractRule implements ValidationRule, DataAwareRule, ValidatorAwareRule
{

    protected array     $data;
    protected Validator $validator;

    public function setData(array $data)
    {
        $this->data = $data;
    }


    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;
    }


}
