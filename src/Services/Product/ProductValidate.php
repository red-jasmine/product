<?php

namespace RedJasmine\Product\Services\Product;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use JsonException;
use RedJasmine\Product\Services\Product\Validators\AbstractProductValidator;
use RedJasmine\Product\Services\Product\Validators\BasicValidator;
use RedJasmine\Product\Services\Product\Validators\PropsValidator;
use RedJasmine\Support\Foundation\Service\WithUserService;

class ProductValidate
{

    use WithUserService;

    /**
     * 基础验证器
     * @var AbstractProductValidator[]
     */
    public static array $validators = [
        BasicValidator::class,
        PropsValidator::class
    ];
    protected array $data = [];
    protected \Illuminate\Validation\Validator $validator;

    /**
     * @param array $data
     *
     * @throws JsonException
     */
    public function __construct(array $data)
    {
        $this->setData($data);
        $this->validator = $this->initValidator();
    }

    /**
     * @param $data
     *
     * @return $this
     * @throws JsonException
     */
    public function setData($data) : ProductValidate
    {

        $saleProps = $data['info']['sale_props'] ?? [];
        if (is_string($saleProps) && filled($saleProps)) {
            $data['info']['sale_props'] = json_decode($saleProps, true, 512, JSON_THROW_ON_ERROR);
        }
        $basicProps = $data['info']['basic_props'] ?? [];
        if (is_string($basicProps) && filled($basicProps)) {
            $data['info']['basic_props'] = json_decode($basicProps, true, 512, JSON_THROW_ON_ERROR);
        }
        $skus = $data['skus'] ?? [];
        if (is_string($skus) && filled($skus)) {
            $data['skus'] = json_decode($skus, true, 512, JSON_THROW_ON_ERROR);
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
                $productValidator->setOwner($this->getOwner())->setOperator($this->getOperator());

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
                    $name = 'skus.' . $index . '.' . $key;

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
