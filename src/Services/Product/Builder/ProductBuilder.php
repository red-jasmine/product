<?php

namespace RedJasmine\Product\Services\Product\Builder;

use Exception;

use RedJasmine\Product\Services\Product\Contracts\ProductBuilderInterface;
use RedJasmine\Product\Services\Product\ValidatorService;
use RedJasmine\Support\Helpers\ID\Snowflake;

class ProductBuilder implements ProductBuilderInterface
{


    /**
     * 生成 ID
     * @return int
     * @throws Exception
     */
    public function generateID() : int
    {
        return Snowflake::getInstance()->nextId();
    }


    public function validate(array $data) : array
    {
        $validatorService = new ValidatorService($data);

        return $validatorService->validate();
    }


    public function validateOnly($data)
    {
        $validatorService = new ValidatorService($data);

        return $validatorService->validateOnly();
    }


}
