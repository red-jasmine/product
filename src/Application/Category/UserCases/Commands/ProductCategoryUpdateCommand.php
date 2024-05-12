<?php

namespace RedJasmine\Product\Application\Category\UserCases\Commands;

use RedJasmine\Product\Domain\Category\Enums\CategoryStatusEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class ProductCategoryUpdateCommand extends ProductCategoryCreateCommand
{

    public int $id;


}
