<?php

namespace RedJasmine\Product\Pipelines\Products;

use RedJasmine\Product\DataTransferObjects\ProductDTO;
use RedJasmine\Product\DataTransferObjects\ProductSkuDTO;
use RedJasmine\Product\Models\Product;
use RedJasmine\Support\Enums\BoolIntEnum;

class ProductFillPipeline
{

    public function handle(Product $product, \Closure $next)
    {
        /**
         * @var $productDTO ProductDTO
         */
        $productDTO = $product->getDTO();

        $this->fillProduct($product, $productDTO);

        if ($product->is_multiple_spec === BoolIntEnum::YES) {

            $productDTO->skus->each(function ($skuDTO) use ($product) {
                $sku = new Product();
                $this->fillSku($sku, $skuDTO);
                $product->skus->add($sku);
            });

        }
        return $next($product);
    }

    public function fillProduct(Product $product, ProductDTO $productDTO) : void
    {
        $product->owner              = $productDTO->owner;
        $product->product_type       = $productDTO->productType;
        $product->shipping_type      = $productDTO->shippingType;
        $product->title              = $productDTO->title;
        $product->image              = $productDTO->image;
        $product->barcode            = $productDTO->barcode;
        $product->outer_id           = $productDTO->outerId;
        $product->keywords           = $productDTO->keywords;
        $product->spu_id             = 0;
        $product->is_multiple_spec   = $productDTO->isMultipleSpec;
        $product->is_sku             = $product->is_multiple_spec === BoolIntEnum::YES ? BoolIntEnum::NO : BoolIntEnum::YES;
        $product->sort               = $productDTO->sort;
        $product->status             = $productDTO->status;
        $product->price              = $productDTO->price;
        $product->market_price       = $productDTO->marketPrice;
        $product->cost_price         = $productDTO->costPrice;
        $product->brand_id           = $productDTO->brandId;
        $product->category_id        = $productDTO->categoryId;
        $product->seller_category_id = $productDTO->sellerCategoryId;
        $product->properties         = $productDTO->properties;
        $product->properties_name    = $productDTO->propertiesName;
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
