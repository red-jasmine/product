<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ProductSeriesReadRepository extends QueryBuilderReadRepository implements ProductSeriesReadRepositoryInterface
{


    /**
     * @var $modelClass class-string
     */
    protected string $modelClass = ProductSeries::class;

}
