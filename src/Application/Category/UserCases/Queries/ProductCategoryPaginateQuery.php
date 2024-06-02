<?php

namespace RedJasmine\Product\Application\Category\UserCases\Queries;

use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class ProductCategoryPaginateQuery extends PaginateQuery
{

    public ?int $parentId = null;

}
