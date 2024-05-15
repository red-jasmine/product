<?php

namespace RedJasmine\Product\Tests\Fixtures\Property;

use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyGroupCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyGroupUpdateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;

class ProductPropertyValueFaker
{

    public function data() : array
    {
        return [
            'group_id'    => 0,
            'name'        => fake()->name,
            'sort'        => fake()->randomNumber(),
            'status'      => PropertyStatusEnum::ENABLE->value,
            'extend_info' => [],
        ];
    }

    public function createCommand(array $data) : ProductPropertyValueCreateCommand
    {
        return ProductPropertyValueCreateCommand::from(array_merge($this->data(), $data));
    }


    public function updateCommand(array $data) : ProductPropertyValueUpdateCommand
    {
        return ProductPropertyGroupUpdateCommand::from(array_merge($this->data(), $data));
    }

}
