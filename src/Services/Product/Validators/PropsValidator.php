<?php

namespace RedJasmine\Product\Services\Product\Validators;

use Illuminate\Validation\Validator;
use RedJasmine\Product\Services\Product\Validators\Rules\SalePropsRule;

class PropsValidator extends AbstractProductValidator
{
    public function withValidator(Validator $validator) : void
    {
        $validator->after(function (Validator $validator) {


        });
    }


    public function rules() : array
    {
        return [
            'info.basic_props' => [],
            'info.sale_props'  => [ new SalePropsRule() ],
        ];
    }


}
