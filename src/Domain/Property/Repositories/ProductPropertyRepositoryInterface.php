<?php

namespace RedJasmine\Product\Domain\Property\Repositories;


use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;


/**
 * @method ProductProperty  find($id)
 */
interface ProductPropertyRepositoryInterface extends RepositoryInterface
{


    public function findByName(string $name);

}
