<?php

namespace RedJasmine\Product\Application\Category\UserCases\Queries;

use RedJasmine\Support\Data\Data;

class ProductCategoryTreeQuery extends Data
{
    public string|array|null $includes;

    public string|array|null $fields;

    public string|array|null $append;

    public string|array|null $sort;


}
