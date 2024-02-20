<?php

namespace RedJasmine\Product\DataTransferObjects\Series;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
class ProductSeriesDTO extends Data
{

    public string $name;

    public ?string $remaks = null;

    /**
     * @var DataCollection<ProductSeriesProductDTO>
     */
    public DataCollection $products;

}
