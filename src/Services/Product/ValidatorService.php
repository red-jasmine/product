<?php

namespace RedJasmine\Product\Services\Product;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use RedJasmine\Product\Services\Product\Validators\AbstractProductValidator;
use RedJasmine\Product\Services\Product\Validators\ProductBasicValidator;
use RedJasmine\Product\Services\Product\Validators\PropsValidator;
use RedJasmine\Support\Traits\WithUserService;

class ValidatorService
{

    use WithUserService;

    public function __construct(protected array $data)
    {

        $this->validator = $this->initValidator();
    }


    /**
     * 基础验证器
     * @var AbstractProductValidator[]
     */
    public static array $validators = [
        ProductBasicValidator::class,
        PropsValidator::class
    ];


    /**
     * @return array|AbstractProductValidator[]
     */
    protected function getValidators() : array
    {
        $validators = self::$validators;

        $configValidators = config('red-jasmine.product.validators');

        return array_merge($validators, $configValidators);
    }


    protected \Illuminate\Validation\Validator $validator;

    public function initValidator() : \Illuminate\Validation\Validator
    {
        $validator = Validator::make($this->data, []);
        foreach ($this->getValidators() as $validatorName) {
            $productValidator = app($validatorName);
            if ($productValidator instanceof AbstractProductValidator) {
                $productValidator->setOwner($this->getOwner())->setOperator($this->getOperator());

                // 加载后续验证器
                $productValidator->withValidator($validator);

                $validator->addRules($productValidator->rules());
                $validator->addCustomAttributes($productValidator->attributes());

            }
        }
        return $validator;

    }

    public function validator() : \Illuminate\Validation\Validator
    {
        return $this->validator;
    }

    public function validateOnly() : array
    {
        $validator = $this->initValidator();
        $validator->setRules(Arr::only($validator->getRules(), array_keys($this->data)));
        $validator->validated();
        return $validator->safe()->all();
    }

    public function validate() : array
    {
        $this->validator->validated();
        return $this->validator->safe()->all();
    }


}
