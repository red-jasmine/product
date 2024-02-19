<?php

namespace RedJasmine\Product\DataTransferObjects;

use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Support\Enums\BoolIntEnum;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
class ProductSkuDTO extends Data
{
    public ProductStatusEnum     $status;
    public string                $properties;
    public ?string               $propertiesName;
    public int                   $stock;
    public string|int|float      $price;
    public string|int|float|null $marketPrice = null;
    public string|int|float|null $costPrice   = null;
    public string|Optional|null  $image       = null;
    public string|Optional|null  $barcode     = null;
    public string|Optional|null  $outerId     = null;
}
