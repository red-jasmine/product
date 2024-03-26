<?php

namespace RedJasmine\Product\Services\Series\Data;

use Illuminate\Support\Collection;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\DataTransferObjects\UserData;


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
