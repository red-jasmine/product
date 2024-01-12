<?php

namespace RedJasmine\Product\Validators;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use RedJasmine\Product\Services\Product\Validators\AbstractProductValidator;
use RedJasmine\Product\Services\Product\Validators\BasicValidator;
use RedJasmine\Product\Services\Product\Validators\PropsValidator;
use RedJasmine\Support\Helpers\Json\Json;
use Spatie\LaravelData\Data;

class ProductValidator
{

    /**
     * 基础验证器
     * @var AbstractProductValidator[]
     */
    public static array $validators = [
        BasicValidator::class,
        PropsValidator::class
    ];

    protected array                            $data = [];
    protected \Illuminate\Validation\Validator $validator;


    /**
     * @param Data $DTO
     *
     */
    public function __construct(protected Data $DTO)
    {
        $this->setData($DTO->toArray());
        $this->validator = $this->initValidator();
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function setData($data) : ProductValidator
    {
        if (isset($data['info']['sale_props'])) {
            $data['info']['sale_props'] = Json::toArray($data['info']['sale_props']);
        }
        if (isset($data['info']['basic_props'])) {
            $data['info']['basic_props'] = Json::toArray($data['info']['basic_props']);
        }
        if (isset($data['skus'])) {
            $data['skus'] = Json::toArray($data['skus']);
        }
        $this->data = $data;
        return $this;
    }

    public function initValidator() : \Illuminate\Validation\Validator
    {
        $validator = Validator::make($this->data, [], [], []);

        foreach ($this->getValidators() as $validatorName) {
            $productValidator = app($validatorName);
            if ($productValidator instanceof AbstractProductValidator) {
                $productValidator->setDTO($this->DTO);
                // 加载后续验证器
                $productValidator->withValidator($validator);
                $validator->addRules($productValidator->rules());
                $validator->addCustomAttributes($productValidator->attributes());
                $validator->setCustomMessages($productValidator->messages());
            }
        }
        return $validator;

    }

    /**
     * @return array|AbstractProductValidator[]
     */
    protected function getValidators() : array
    {
        $validators = self::$validators;

        $configValidators = config('red-jasmine.product.validators');

        return array_merge($validators, $configValidators);
    }

    public function validator() : \Illuminate\Validation\Validator
    {
        return $this->validator;
    }

    public function validateOnly() : array
    {
        $validator = $this->initValidator();
        $keys      = array_keys($this->data);

        foreach ($this->data['info'] ?? [] as $key => $value) {
            $keys[] = 'info.' . $key;
        }

        if (filled($this->data['skus'] ?? [])) {
            foreach ($this->data['skus'] as $index => $sku) {
                foreach ($sku as $key => $value) {
                    $name        = 'skus.' . $index . '.' . $key;
                    $keys[$name] = $name;
                }
            }
        }

        // TODO skus 这个是数组没有获取所有的 *

        $validator->setRules(Arr::only($validator->getRules(), array_values($keys)));
        $validator->validate();
        // skus 不是安全的
        return $validator->safe()->all();
    }

    public function validate() : array
    {
        $this->validator->validate();
        return $this->validator->safe()->all();
    }


}
