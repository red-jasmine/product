<?php

namespace RedJasmine\Product\DataTransferObjects;

use RedJasmine\Product\Enums\Product\FreightPayerEnum;
use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Product\Enums\Product\ProductTypeEnum;
use RedJasmine\Product\Enums\Product\ShippingTypeEnum;
use RedJasmine\Product\Enums\Product\SubStockTypeEnum;
use RedJasmine\Support\DataTransferObjects\UserDTO;
use RedJasmine\Support\Enums\BoolIntEnum;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;

class ProductUpdateDTO extends Data
{
    public array|null|Optional            $parameters;
    public string|Optional                $title;
    public ProductTypeEnum|Optional       $productType;
    public ShippingTypeEnum|Optional      $shippingType;
    public ProductStatusEnum|Optional     $status;
    public int|Optional                   $stock;
    public string|int|float|Optional      $price;
    public string|Optional|null           $image;
    public string|null|Optional           $barcode;
    public string|null|Optional           $outerId;
    public string|null|Optional           $keywords;
    public int|Optional                   $sort;
    public BoolIntEnum|Optional           $isMultipleSpec;
    public string|int|float|null|Optional $marketPrice;
    public string|int|float|null|Optional $costPrice;
    public int|Optional|null              $brandId;
    public int|Optional|null              $categoryId;
    public int|Optional|null              $sellerCategoryId;
    public int|Optional|null              $postageId;
    public int|Optional|null              $min;
    public int|Optional|null              $max;
    public int|Optional                   $multiple;
    public int|Optional                   $fakeSales;
    public int|Optional                   $deliveryTime;
    public int|Optional                   $vip;
    public int|Optional                   $points;
    public int|Optional                   $isHot;
    public int|Optional                   $isNew;
    public int|Optional                   $isBest;
    public int|Optional                   $isBenefit;
    public string|null|Optional           $properties;
    public string|null|Optional           $propertiesName;
    public FreightPayerEnum|Optional      $freightPayer;
    public SubStockTypeEnum|Optional      $subStock;

    public ProductInfoDTO|Optional $info;


    /**
     * @var DataCollection<ProductSkuDTO>|Optional
     */
    public DataCollection|Optional $skus;


}
