<?php

namespace RedJasmine\Product\Application\Category\UserCases\Queries;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Data\UserData;

class ProductSellerCategoryTreeQuery extends Data
{

    public static function morphs() : array
    {
        return [ 'owner' ];
    }

    public UserData $owner;

    public string|array|null $include;

    public string|array|null $fields;

    public string|array|null $append;

    public string|array|null $sort;


}
