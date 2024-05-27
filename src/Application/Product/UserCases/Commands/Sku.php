<?php

namespace RedJasmine\Product\Application\Product\UserCases\Commands;

use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Optional;

class Sku extends Data
{
    public string                $properties;
    public ?string               $propertiesName;
    public string                $price;
    public ProductStatusEnum     $status      = ProductStatusEnum::ON_SALE;
    public int                   $stock       = 0;
    public int                   $safetyStock = 0;
    public string|int|float|null $marketPrice = null;
    public string|int|float|null $costPrice   = null;
    public string|Optional|null  $image       = null;
    public string|Optional|null  $barcode     = null;
    public string|Optional|null  $outerId     = null;
}
