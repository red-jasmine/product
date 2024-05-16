<?php

namespace RedJasmine\Product\Application\Series\UserCases\Commands;

use Illuminate\Support\Collection;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Data\UserData;

class ProductSeriesCreateCommand extends Data
{
    public static function morphs() : array
    {
        return [
            'owner'
        ];
    }

    public UserData $owner;

    public string $name;

    public ?string $remarks = null;

    /**
     * @var Collection<ProductSeriesProductData>
     */
    public Collection $products;


}
