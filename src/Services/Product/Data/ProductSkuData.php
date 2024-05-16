<?php

namespace RedJasmine\Product\Services\Product\Data;

use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Optional;

class ProductSkuData extends Data
{
    public string                $properties;
    public string|int|float      $price;
    public ?ProductStatusEnum    $status;
    public ?string               $propertiesName;
    public int                   $stock        = 0;
    public int                   $virtualStock = 0;
    public int                   $safetyStock  = 0;
    public string|int|float|null $marketPrice  = null;
    public string|int|float|null $costPrice    = null;
    public string|Optional|null  $image        = null;
    public string|Optional|null  $barcode      = null;
    public string|Optional|null  $outerId      = null;
}
