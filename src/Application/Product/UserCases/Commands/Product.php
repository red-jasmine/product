<?php

namespace RedJasmine\Product\Application\Product\UserCases\Commands;

use Illuminate\Support\Collection;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\PromiseServices;
use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\SubStockTypeEnum;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Data\UserData;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class Product extends Data
{
    public static function morphs() : array
    {
        return [
            'owner',
            'supplier'
        ];
    }

    public UserData $owner;



    public string $title;

    public int     $stock   = 0;
    public Amount  $price;
    public Amount  $marketPrice;
    public Amount  $costPrice;
    public ?string $image   = null;
    public ?string $barcode = null;
    public ?string $outerId = null;
    public int     $unit    = 1;


    public ProductTypeEnum   $productType;
    public ShippingTypeEnum  $shippingType;
    public ProductStatusEnum $status       = ProductStatusEnum::ON_SALE;
    public FreightPayerEnum  $freightPayer = FreightPayerEnum::DEFAULT;
    public SubStockTypeEnum  $subStock     = SubStockTypeEnum::DEFAULT;
    public int               $deliveryTime = 0;

    public ?int $sort             = 0;
    public bool $isMultipleSpec   = false;
    public int  $brandId          = 0;
    public int  $categoryId       = 0;
    public int  $sellerCategoryId = 0;
    public int  $postageId        = 0;
    public ?int $minLimit         = 0;
    public ?int $maxLimit         = 0;
    public int  $stepLimit        = 1;

    public int  $vip         = 0;
    public int  $points      = 0;
    public bool $isHot       = false;
    public bool $isNew       = false;
    public bool $isBest      = false;
    public bool $isBenefit   = false;
    public int  $safetyStock = 0;


    public ?string $keywords    = null;
    public ?string $description = null;

    public ?string $detail = null;
    public ?array  $images = null;
    public ?array  $videos = null;
    public ?string $weight;
    public ?string $width;
    public ?string $height;
    public ?string $length;
    public ?string $size;
    public ?string $remarks;
    public ?array  $tools;
    public ?array  $expands;

    /**
     * @var PromiseServices|null
     */
    public ?PromiseServices $promiseServices;


    /**
     * 基础属性
     * @var Collection<Property>|null
     */
    public ?Collection $basicProps = null;
    /**
     * 销售属性
     * @var Collection<Property>|null
     */
    public ?Collection $saleProps = null;

    /**
     * 规格集合
     * @var Collection<Sku>|null
     */
    public ?Collection $skus = null;




}
