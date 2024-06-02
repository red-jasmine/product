<?php

namespace RedJasmine\Product\Application\Property\UserCases\Queries;

use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class PropertyGroupPaginateQuery extends PaginateQuery
{
    public ?string $name;

    public ?string $status;


}
