<?php

namespace RedJasmine\Product\Services\Product;

use RedJasmine\Product\Services\Product\Validators\AbstractProductValidator;
use RedJasmine\Product\Services\Product\Validators\ProductBasicValidator;
use RedJasmine\Support\Traits\WithUserService;

class ValidatorService
{

    use WithUserService;

    public function __construct(protected array $data)
    {
    }


    /**
     * 基础验证器
     * @var AbstractProductValidator[]
     */
    public static array $validators = [
        ProductBasicValidator::class,
    ];


    /**
     * @return array|AbstractProductValidator[]
     */
    public function getValidators() : array
    {
        $validators = self::$validators;

        $configValidators = config('red-jasmine.product.validators');

        return array_merge($validators, $configValidators);
    }

    public function validate() : void
    {
        foreach ($this->getValidators() as $validatorName) {
            $validator = app($validatorName);
            if ($validator instanceof AbstractProductValidator) {
                $validator->setOwner($this->getOwner());
                $validator->setOperator($this->getOperator());
                $validator->validator($this->data);
            }
        }
    }


}
