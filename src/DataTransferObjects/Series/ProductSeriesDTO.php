<?php

namespace RedJasmine\Product\DataTransferObjects\Series;

use RedJasmine\Support\DataTransferObjects\Data;
use Spatie\LaravelData\DataCollection;


class ProductSeriesDTO extends Data
{

    public string $name;

    public ?string $remaks = null;

    /**
     * @var DataCollection<ProductSeriesProductDTO>
     */
    public DataCollection $products;

}