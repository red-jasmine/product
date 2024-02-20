<?php

namespace RedJasmine\Product\Pipelines\Products;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use RedJasmine\Product\DataTransferObjects\ProductDTO;
use RedJasmine\Product\DataTransferObjects\ProductModifyDTO;
use RedJasmine\Product\DataTransferObjects\ProductSkuDTO;
use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductSku;
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


    public function fillProduct(Product $product, ProductDTO $productDTO) : void
    {

        $product->owner = $productDTO->owner;

        $product->product_type       = $productDTO->productType;
        $product->shipping_type      = $productDTO->shippingType;
        $product->title              = $productDTO->title;
        $product->image              = $productDTO->image;
        $product->barcode            = $productDTO->barcode;
        $product->outer_id           = $productDTO->outerId;
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
        $product->delivery_time      = $productDTO->deliveryTime;
        $product->vip                = $productDTO->vip;
        $product->points             = $productDTO->points;
        $product->is_hot             = $productDTO->isHot;
        $product->is_new             = $productDTO->isNew;
        $product->is_best            = $productDTO->isBest;
        $product->is_benefit         = $productDTO->isBenefit;

        $product->info->description = $productDTO->info?->description;
        $product->info->detail      = $productDTO->info?->detail;
        $product->info->images      = $productDTO->info?->images;
        $product->info->videos      = $productDTO->info?->videos;
        $product->info->weight      = $productDTO->info?->weight;
        $product->info->width       = $productDTO->info?->width;
        $product->info->height      = $productDTO->info?->height;
        $product->info->length      = $productDTO->info?->length;
        $product->info->size        = $productDTO->info?->size;
        $product->info->basic_props = $productDTO->info?->basicProps;
        $product->info->sale_props  = $productDTO->info?->saleProps;
        $product->info->remarks     = $productDTO->info?->remarks;
        $product->info->tools       = $productDTO->info?->tools;
        $product->info->extends     = $productDTO->info?->extends;


    }

    protected function fillSkus(Product $product) : void
    {
        /**
         * @var $productDTO ProductDTO|ProductModifyDTO
         */
        $productDTO = $product->getDTO();


        // 当前的所有 SKU
        $product->setRelation('skus', $product->skus->keyBy('properties'));

        // 获取数据库中所有的SKU
        /**
         * @var Collection|array|Product[] $all
         */
        $allSku = $product->skus()->withTrashed()->get()->keyBy('properties');

        if ($product->is_multiple_spec === BoolIntEnum::NO) {
            // TODO 如果是单规格商品

            $skuDTO                 = new ProductSkuDTO();
            $skuDTO->properties     = '';
            $skuDTO->propertiesName = null;
            $sku                    = $allSku[$skuDTO->properties] ?? $product->skus[$skuDTO->properties] ?? new ProductSku();

            $data   = $productDTO->toArray();
            $data   = Arr::only($data, [ 'image', 'barcode', 'outer_id', 'stock', 'price', 'market_price', 'cost_price' ]);
            $fields = [ 'status', 'image', 'barcode', 'outer_id', 'stock', 'price', 'market_price', 'cost_price', 'sales' ];
            foreach ($fields as $field) {
                $skuDTO->{$field} = $data[$field] ?? ($sku->{$field} ?? $product->{$field});
            }

            $this->fillSku($sku, $skuDTO);
            $product->skus[$skuDTO->properties] = $sku;
        }

        // 如果是多规格
        if ($product->is_multiple_spec === BoolIntEnum::YES) {

            $productDTO->skus?->each(function ($skuDTO) use ($product, $allSku) {
                /**
                 * @var $skuDTO ProductSkuDTO
                 */
                $skuDTO->properties = $this->propertyFormatter->formatString($skuDTO->properties);
                $sku                = $allSku[$skuDTO->properties] ?? $product->skus[$skuDTO->properties] ?? new ProductSku();
                $this->fillSku($sku, $skuDTO);
                $product->skus[$skuDTO->properties] = $sku;
            });
            if ($productDTO->skus) {
                $newSkus = collect($productDTO->skus)->pluck('properties')->values()->toArray();
                $product->skus->pluck('properties')->each(function ($properties) use ($product, $newSkus) {
                    if (!in_array($properties, $newSkus, true)) {
                        unset($product->skus[$properties]);
                    }
                });
            }
        }
    }

    public function fillSku(ProductSku $sku, ProductSkuDTO $productSkuDTO) : void
    {
        $sku->properties      = $productSkuDTO->properties;
        $sku->properties_name = $productSkuDTO->propertiesName;
        $sku->image           = $productSkuDTO->image;
        $sku->barcode         = $productSkuDTO->barcode;
        $sku->outer_id        = $productSkuDTO->outerId;
        $sku->properties      = $productSkuDTO->properties;
        $sku->price           = $productSkuDTO->price;
        $sku->stock           = $productSkuDTO->stock;
        $sku->market_price    = $productSkuDTO->marketPrice;
        $sku->cost_price      = $productSkuDTO->costPrice;
        $sku->status          = $productSkuDTO->status ?? ProductStatusEnum::ON_SALE;


    }


}
