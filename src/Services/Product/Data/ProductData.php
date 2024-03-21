<?php

namespace RedJasmine\Product\Services\Product\Data;

use Illuminate\Support\Collection;
use RedJasmine\Product\Enums\Product\FreightPayerEnum;
use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Product\Enums\Product\ProductTypeEnum;
use RedJasmine\Product\Enums\Product\ShippingTypeEnum;
use RedJasmine\Product\Enums\Product\SubStockTypeEnum;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\DataTransferObjects\UserData;
use RedJasmine\Support\Enums\BoolIntEnum;
use RedJasmine\Support\Helpers\Json\Json;
use Spatie\LaravelData\DataCollection;


class ProductData extends Data
{
    public static function morphs() : array
    {
        return [
            'owner'
        ];
    }

    public UserData              $owner;
    public string                $title;
    public ProductTypeEnum       $productType;
    public ShippingTypeEnum      $shippingType;
    public ProductStatusEnum     $status;
    public int                   $stock;
    public string|int|float      $price;
    public ?string               $image            = null;
    public ?string               $barcode          = null;
    public ?string               $outerId          = null;
    public int                   $sort             = 0;
    public bool                  $isMultipleSpec   = false;
    public string|int|float|null $marketPrice      = null;
    public string|int|float|null $costPrice        = null;
    public ?int                  $brandId          = null;
    public ?int                  $categoryId       = null;
    public ?int                  $sellerCategoryId = null;
    public ?int                  $postageId        = null;
    public ?int                  $min              = null;
    public ?int                  $max              = null;
    public int                   $multiple         = 1;
    public int                   $fakeSales        = 0;
    public int                   $deliveryTime     = 0;
    public int                   $vip              = 0;
    public int                   $points           = 0;
    public int                   $isHot            = 0;
    public int                   $isNew            = 0;
    public int                   $isBest           = 0;
    public int                   $isBenefit        = 0;
    public FreightPayerEnum      $freightPayer     = FreightPayerEnum::DEFAULT;
    public SubStockTypeEnum      $subStock         = SubStockTypeEnum::DEFAULT;

    public ?ProductInfoData $info = null;

    /**
     * @var DataCollection<ProductSkuData>|null
     */
    public ?DataCollection $skus = null;

    public static function prepareForPipeline(Collection $properties) : Collection
    {
        if ($properties->offsetExists('skus')) {
            $properties->put('skus', Json::toArray($properties->get('skus', '')));
        }
        return $properties;
    }


}
