<?php

namespace RedJasmine\Product\Domain\Category\Repositories;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface ProductSellerCategoryReadRepositoryInterface extends ReadRepositoryInterface
{

    public function tree(Data $query) : array;

}
