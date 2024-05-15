<?php

namespace RedJasmine\Product\Tests\Fixtures\Property;

use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyGroupCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyGroupUpdateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;

class ProductPropertyGroupFaker
{

    public function data() : array
    {
        return [
            'name'        => fake()->name,
            'sort'        => fake()->randomNumber(),
            'status'      => PropertyStatusEnum::ENABLE->value,
            'extend_info' => [],
        ];
    }

    public function createCommand(array $data = []) : ProductPropertyGroupCreateCommand
    {
        return ProductPropertyGroupCreateCommand::from(array_merge($this->data(), $data));
    }


    public function updateCommand(array $data = []) : ProductPropertyGroupUpdateCommand
    {
        return ProductPropertyGroupUpdateCommand::from(array_merge($this->data(), $data));
    }

}
