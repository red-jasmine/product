<?php

namespace RedJasmine\Product\DataTransferObjects;

use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Support\DataTransferObjects\Data;
use Spatie\LaravelData\Optional;

class ProductSkuDTO extends Data
{
    public ?ProductStatusEnum    $status;
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
