<?php

namespace RedJasmine\Product\Services\Product\Validators;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;
use RedJasmine\Product\Services\Product\Validators\Rules\HasSkusRule;
use RedJasmine\Product\Services\Product\Validators\Rules\PropsRule;
use RedJasmine\Product\Services\Product\Validators\Rules\SalePropsRule;
use RedJasmine\Product\Services\Product\Validators\Rules\SkusRule;
use RedJasmine\Support\Enums\BoolIntEnum;

class PropsValidator extends AbstractProductValidator
{
    public function withValidator(Validator $validator) : void
    {
        // 在调用之前
        $has_skus = $validator->getValue('has_skus');
        if (BoolIntEnum::from($has_skus) === BoolIntEnum::NO) {
            // 如果不是多规格 重置 销售属性
            $validator->setValue('info.sale_props', []);
            $validator->setValue('skus', []);
        }


        // 验证之后
        $validator->after(function (Validator $validator) {
            $validator->after(function (Validator $validator) {

                $data = $validator->getData();


            });


        });
    }


    public function rules() : array
    {
        return [
            'has_skus'         => [ new HasSkusRule() ],
            'info.basic_props' => [ 'sometimes', new PropsRule() ],
            'info.sale_props'  => [ 'sometimes', new PropsRule(), new SalePropsRule() ],
            'skus'             => [ 'sometimes', new SkusRule() ],
        ];
    }


}
