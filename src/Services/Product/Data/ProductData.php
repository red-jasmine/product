<?php

namespace RedJasmine\Product\Services\Product\Data;

use Illuminate\Support\Collection;
use RedJasmine\Product\Services\Product\Enums\FreightPayerEnum;
use RedJasmine\Product\Services\Product\Enums\ProductStatusEnum;
use RedJasmine\Product\Services\Product\Enums\ProductTypeEnum;
use RedJasmine\Product\Services\Product\Enums\ShippingTypeEnum;
use RedJasmine\Product\Services\Product\Enums\SubStockTypeEnum;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Helpers\Json\Json;


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
     * @var Collection<ProductSkuData>|null
     */
    public ?Collection $skus = null;

    public static function prepareForPipeline(array $properties) : array
    {

        if (isset($properties['skus'])) {
            $properties['skus'] = Json::toArray($properties['skus']);
        }
        if (isset($properties['info']['basic_props'])) {
            $properties['info']['basic_props'] = Json::toArray($properties['info']['basic_props']);
        }
        if (isset($properties['info']['sale_props'])) {
            $properties['info']['sale_props'] = Json::toArray($properties['info']['sale_props']);
        }
        return $properties;
    }


}
