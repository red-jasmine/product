<?php

namespace RedJasmine\Product\Services\Product\Validators;

use Illuminate\Validation\Validator;

interface ValidatorAwareValidatorCombiner
{


    public function setValidator(Validator $validator);


}
