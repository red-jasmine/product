<?php

namespace RedJasmine\Product\Services\Product\Validators;

use Illuminate\Validation\Validator;
use RedJasmine\Support\Foundation\Service\Actions\ResourceAction;

class ValidatorCombiner implements ValidatorCombinerInterface, ActionAwareValidatorCombiner, ValidatorAwareValidatorCombiner
{

    protected Validator $validator;

    protected ResourceAction $action;

    public function setAction(ResourceAction $action) : void
    {
        $this->action = $action;
    }

    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;

    }


    public function rules() : array
    {
        return [];
    }


    public function messages() : array
    {
        return [];
    }

    public function attributes() : array
    {
        return [];
    }


}
