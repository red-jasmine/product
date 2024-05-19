<?php

namespace RedJasmine\Product\Domain\Property\Repositories;


use RedJasmine\Support\Domain\Repositories\RepositoryInterface;


interface ProductPropertyValueRepositoryInterface extends RepositoryInterface
{


    public function findByNameInProperty(int $pid, string $name);

}
