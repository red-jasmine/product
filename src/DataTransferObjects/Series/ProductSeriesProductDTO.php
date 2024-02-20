<?php

namespace RedJasmine\Product\DataTransferObjects\Series;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;


#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
class ProductSeriesProductDTO extends Data
{

    public int $productId;

    public string $name;

}
