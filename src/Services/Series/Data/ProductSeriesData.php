<?php

namespace RedJasmine\Product\Services\Series\Data;

use Illuminate\Support\Collection;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Data\UserData;


class ProductSeriesData extends Data
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
