<?php

namespace RedJasmine\Product\Domain\Category\Repositories;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Infrastructure\ReadRepositories\ReadRepositoryInterface;

interface ProductSellerCategoryReadRepositoryInterface extends ReadRepositoryInterface
{

    public function tree(Data $query) : array;

}
