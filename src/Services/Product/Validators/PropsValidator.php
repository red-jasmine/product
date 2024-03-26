<?php

namespace RedJasmine\Product\Services\Product\Validators;

use Illuminate\Validation\Validator;
use RedJasmine\Product\Services\Product\Validators\Rules\HasSkusRule;
use RedJasmine\Product\Services\Product\Validators\Rules\PropsCheckRule;
use RedJasmine\Product\Services\Product\Validators\Rules\PropsRule;
use RedJasmine\Product\Services\Product\Validators\Rules\SalePropsRule;
use RedJasmine\Product\Services\Product\Validators\Rules\SkusRule;
use RedJasmine\Support\Enums\BoolIntEnum;
use RedJasmine\Support\Foundation\Service\Actions\ValidatorCombiner;

class PropsValidator extends ValidatorCombiner
{

    protected bool $hasSkuRules = false;

    public function setValidator(Validator $validator) : void
    {
        // 在调用之前
        $is_multiple_spec = $validator->getData()['is_multiple_spec'] ?? 0;
        if ((bool)$is_multiple_spec === true) {
            $this->hasSkuRules = true;
        }
        if ((bool)$is_multiple_spec === false) {
            // 如果不是多规格 重置 销售属性
            $validator->setValue('info.sale_props', []);
            $validator->setValue('skus', null);
            $this->hasSkuRules = false;
            return;
        }
        // 如果传了SKU 不传 是否多规格 那么就必须验证
        if ($is_multiple_spec === null && filled($validator->getValue('skus'))) {
            $this->hasSkuRules = true;
        }


    }

    public function attributes() : array
    {
        return collect($this->fields())->keys()->combine(collect($this->fields())->pluck('attribute'))->toArray();

    }

    public function fields() : array
    {
        $fields = [
            'is_multiple_spec' => [ 'attribute' => '多规格', 'rules' => [ new HasSkusRule() ] ],
            'info.sale_props'  => [ 'attribute' => '销售属性', 'rules' => [ 'sometimes', new PropsRule(), new SalePropsRule(), new SkusRule(), new PropsCheckRule() ] ],
            'info.basic_props' => [ 'attribute' => '基础属性', 'rules' => [ 'sometimes', new PropsRule(), new PropsCheckRule() ] ],
        ];

        if ($this->hasSkuRules) {
            $basicValidator = new BasicValidator();
            return array_merge($fields, $basicValidator->skuRules());
        }
        return $fields;
    }

    public function rules() : array
    {
        return collect($this->fields())->keys()->combine(collect($this->fields())->pluck('rules'))->toArray();
    }


}
