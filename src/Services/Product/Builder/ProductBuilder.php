<?php

namespace RedJasmine\Product\Services\Product\Builder;

use Exception;
use RedJasmine\Product\Services\Product\Contracts\ProductBuilderInterface;
use RedJasmine\Product\Services\Product\ProductValidate;
use RedJasmine\Support\Helpers\ID\Snowflake;
use RedJasmine\Support\Traits\Services\WithUserService;

class ProductBuilder implements ProductBuilderInterface
{

    use WithUserService;


    /**
     * 生成 ID
     * @return int
     * @throws Exception
     */
    public function generateID() : int
    {
        return Snowflake::getInstance()->nextId();
    }


    /**
     * @param array $data
     *
     * @return array
     * @throws \JsonException
     */
    public function validate(array $data) : array
    {
        $validatorService = new ProductValidate($data);
        return $validatorService->validate();
    }


    /**
     * @param $data
     *
     * @return array
     * @throws \JsonException
     */
    public function validateOnly($data) : array
    {
        $validatorService = new ProductValidate($data);

        return $validatorService->validateOnly();
    }


}
