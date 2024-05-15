<?php

namespace RedJasmine\Product\Tests\Fixtures\Property;

use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;

class ProductPropertyFaker
{

    public function data() : array
    {
        return [
            'name'        => fake()->name,
            'sort'        => fake()->randomNumber(),
            'group_id'    => 0,
            'extend_info' => [],
            'status'      => PropertyStatusEnum::ENABLE->value
        ];
    }

    public function createCommand(array $data = []) : ProductPropertyCreateCommand
    {
        return ProductPropertyCreateCommand::from(array_merge($this->data(), $data));
    }


    public function updateCommand(array $data = []) : ProductPropertyUpdateCommand
    {
        return ProductPropertyUpdateCommand::from(array_merge($this->data(), $data));
    }

}
