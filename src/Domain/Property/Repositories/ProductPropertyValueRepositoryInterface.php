<?php

namespace RedJasmine\Product\Domain\Property\Repositories;


use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;


/**
 * @method ProductPropertyValue find($id)
 */
interface ProductPropertyValueRepositoryInterface extends RepositoryInterface
{


    /**
     * @param int    $pid
     * @param string $name
     *
     * @return ProductPropertyValue
     */
    public function findByNameInProperty(int $pid, string $name);

}
