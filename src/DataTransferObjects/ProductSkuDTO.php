<?php

namespace RedJasmine\Product\DataTransferObjects;

use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Support\Enums\BoolIntEnum;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
class ProductSkuDTO extends Data
{
    public ProductStatusEnum     $status;
    public BoolIntEnum           $isSku          = BoolIntEnum::YES;
    public int                   $stock;
    public string|int|float      $price;
    public ?string               $image          = null;
    public ?string               $barcode        = null;
    public ?string               $outerId        = null;
    public ?string               $keywords       = null;
    public int                   $sort           = 0;
    public string|int|float|null $marketPrice    = null;
    public string|int|float|null $costPrice      = null;
    public ?int                  $min            = null;
    public ?int                  $max            = null;
    public int                   $multiple       = 1;
    public ?string               $properties     = null;
    public ?string               $propertiesName = null;

}
