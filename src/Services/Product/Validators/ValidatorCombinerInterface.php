<?php

namespace RedJasmine\Product\Services\Product\Validators;

interface ValidatorCombinerInterface
{

    public function rules() : array;

    public function messages() : array;

    public function attributes() : array;
}
