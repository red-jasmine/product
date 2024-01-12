<?php

namespace RedJasmine\Product\Pipelines\Products;

use RedJasmine\Product\DataTransferObjects\ProductDTO;
use RedJasmine\Product\DataTransferObjects\ProductSkuDTO;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Services\Property\PropertyFormatter;
use RedJasmine\Support\Enums\BoolIntEnum;

class ProductFillPipeline
{
    public function __construct(protected PropertyFormatter $propertyFormatter)
    {
    }


    public function before(Product $product, \Closure $next)
    {
        return $next($product);
    }


    public function handle(Product $product, \Closure $next)
    {

        /**
         * @var $productDTO ProductDTO
         */
        $productDTO = $product->getDTO();

        $this->fillProduct($product, $productDTO);

        $this->fillSkus($product);

        return $next($product);
    }


    protected function fillSkus(Product $product) : void
    {
        /**
         * @var $productDTO ProductDTO
         */
        $productDTO = $product->getDTO();

        if ($product->is_multiple_spec === BoolIntEnum::YES) {
            $product->setRelation('skus', $product->skus->keyBy('properties'));
            $productDTO->skus->each(function ($skuDTO) use ($product) {
                /**
                 * @var $skuDTO ProductSkuDTO
                 */
                $skuDTO->properties = $this->propertyFormatter->formatString($skuDTO->properties);
                $sku                = $product->skus[$skuDTO->properties] ?? new Product();
                $this->fillSku($sku, $skuDTO);
                $product->skus[$skuDTO->properties] = $sku;
            });
            $newSkus = collect($productDTO->skus)->pluck('properties')->values()->toArray();
            $product->skus->pluck('properties')->each(function ($properties) use ($product, $newSkus) {
                if (!in_array($properties, $newSkus, true)) {
                    unset($product->skus[$properties]);
                }
            });

        }
    }

    public function fillProduct(Product $product, ProductDTO $productDTO) : void
    {

        $product->owner  = $productDTO->owner;
        $product->spu_id = 0;

        $product->product_type       = $productDTO->productType;
        $product->shipping_type      = $productDTO->shippingType;
        $product->title              = $productDTO->title;
        $product->image              = $productDTO->image;
        $product->barcode            = $productDTO->barcode;
        $product->outer_id           = $productDTO->outerId;
        $product->keywords           = $productDTO->keywords;
        $product->is_multiple_spec   = $productDTO->isMultipleSpec;
        $product->sort               = $productDTO->sort;
        $product->status             = $productDTO->status;
        $product->price              = $productDTO->price;
        $product->market_price       = $productDTO->marketPrice;
        $product->cost_price         = $productDTO->costPrice;
        $product->brand_id           = $productDTO->brandId;
        $product->category_id        = $productDTO->categoryId;
        $product->seller_category_id = $productDTO->sellerCategoryId;
        $product->freight_payer      = $productDTO->freightPayer;
        $product->postage_id         = $productDTO->postageId;
        $product->min                = $productDTO->min;
        $product->max                = $productDTO->max;
        $product->multiple           = $productDTO->multiple;
        $product->sub_stock          = $productDTO->subStock;
        $product->stock              = $productDTO->stock;
        $product->fake_sales         = $productDTO->fakeSales;
        $product->delivery_time      = $productDTO->deliveryTime;
        $product->vip                = $productDTO->vip;
        $product->points             = $productDTO->points;
        $product->is_hot             = $productDTO->isHot;
        $product->is_new             = $productDTO->isNew;
        $product->is_best            = $productDTO->isBest;
        $product->is_benefit         = $productDTO->isBenefit;
        $product->info->desc         = $productDTO->info?->desc;
        $product->info->web_detail   = $productDTO->info?->webDetail;
        $product->info->wap_detail   = $productDTO->info?->wapDetail;
        $product->info->images       = $productDTO->info?->images;
        $product->info->videos       = $productDTO->info?->videos;
        $product->info->weight       = $productDTO->info?->weight;
        $product->info->width        = $productDTO->info?->width;
        $product->info->height       = $productDTO->info?->height;
        $product->info->length       = $productDTO->info?->length;
        $product->info->size         = $productDTO->info?->size;
        $product->info->basic_props  = $productDTO->info?->basicProps;
        $product->info->sale_props   = $productDTO->info?->saleProps;
        $product->info->remarks      = $productDTO->info?->remarks;
        $product->info->tools        = $productDTO->info?->tools;
        $product->info->extends      = $productDTO->info?->extends;


    }


    public function fillSku(Product $sku, ProductSkuDTO $productSkuDTO) : void
    {
        $sku->is_sku           = BoolIntEnum::YES;
        $sku->is_multiple_spec = BoolIntEnum::NO;
        $sku->properties       = $productSkuDTO->properties;
        $sku->properties_name  = $productSkuDTO->propertiesName;
        $sku->status           = $productSkuDTO->status;
        $sku->image            = $productSkuDTO->image;
        $sku->barcode          = $productSkuDTO->barcode;
        $sku->outer_id         = $productSkuDTO->outerId;
        $sku->properties       = $productSkuDTO->properties;
        $sku->price            = $productSkuDTO->price;
        $sku->stock            = $productSkuDTO->stock;
        $sku->market_price     = $productSkuDTO->marketPrice;
        $sku->cost_price       = $productSkuDTO->costPrice;
        $sku->min              = $productSkuDTO->min;
        $sku->max              = $productSkuDTO->max;
        $sku->multiple         = $productSkuDTO->multiple;
    }


}
