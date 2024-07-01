<?php

namespace RedJasmine\Product\Application\Property\UserCases\Queries;

use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class PropertyPaginateQuery extends PaginateQuery
{
    public ?string $name;
    public ?int    $groupId;
    public ?string $status;
    public ?string $type;

}