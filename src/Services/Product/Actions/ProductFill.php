<?php

namespace RedJasmine\Product\Services\Product\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductSku;
use RedJasmine\Product\Services\Product\Data\ProductData;
use RedJasmine\Product\Services\Product\Data\ProductPropData;
use RedJasmine\Product\Services\Product\Data\ProductSkuData;
use RedJasmine\Product\Services\Property\PropertyFormatter;

class ProductFill
{
    public function __construct(protected PropertyFormatter $propertyFormatter)
    {
    }

    /**
     * @param Product     $product
     * @param ProductData $productData
     * @param array       $data
     *
     * @return Product
     * @throws ProductPropertyException
     */
    public function fill(Product $product, ProductData $productData, array $data = []) : Product
    {

        $productData->skus             = collect(ProductSkuData::collect($data['skus'] ?? []));
        $productData->info->basicProps = collect(ProductPropData::collect($data['info']['basic_props'] ?? []));
        $productData->info->saleProps  = collect(ProductPropData::collect($data['info']['sale_props'] ?? []));
        $this->fillProduct($product, $productData);
        $this->fillSkus($product, $productData);
        return $product;
    }

    protected function fillProduct(Product $product, ProductData $productData) : void
    {

        $product->owner              = $productData->owner;
        $product->product_type       = $productData->productType;
        $product->shipping_type      = $productData->shippingType;
        $product->title              = $productData->title;
        $product->image              = $productData->image;
        $product->barcode            = $productData->barcode;
        $product->outer_id           = $productData->outerId;
        $product->is_multiple_spec   = $productData->isMultipleSpec;
        $product->sort               = $productData->sort;
        $product->status             = $productData->status;
        $product->price              = $productData->price;
        $product->market_price       = $productData->marketPrice;
        $product->cost_price         = $productData->costPrice;
        $product->brand_id           = $productData->brandId;
        $product->category_id        = $productData->categoryId;
        $product->seller_category_id = $productData->sellerCategoryId;
        $product->freight_payer      = $productData->freightPayer;
        $product->postage_id         = $productData->postageId;
        $product->min                = $productData->min;
        $product->max                = $productData->max;
        $product->multiple           = $productData->multiple;
        $product->sub_stock          = $productData->subStock;
        $product->delivery_time      = $productData->deliveryTime;
        $product->vip                = $productData->vip;
        $product->points             = $productData->points;
        $product->is_hot             = $productData->isHot;
        $product->is_new             = $productData->isNew;
        $product->is_best            = $productData->isBest;
        $product->is_benefit         = $productData->isBenefit;

        $product->info->description = $productData->info?->description;
        $product->info->detail      = $productData->info?->detail;
        $product->info->images      = $productData->info?->images;
        $product->info->videos      = $productData->info?->videos;
        $product->info->weight      = $productData->info?->weight;
        $product->info->width       = $productData->info?->width;
        $product->info->height      = $productData->info?->height;
        $product->info->length      = $productData->info?->length;
        $product->info->size        = $productData->info?->size;
        $product->info->basic_props = $productData->info?->basicProps;
        $product->info->sale_props  = $productData->info?->saleProps;
        $product->info->remarks     = $productData->info?->remarks;
        $product->info->tools       = $productData->info?->tools;
        $product->info->extends     = $productData->info?->extends;


    }

    /**
     * @param Product     $product
     * @param ProductData $productData
     *
     * @return void
     * @throws ProductPropertyException
     */
    protected function fillSkus(Product $product, ProductData $productData) : void
    {
        // 当前的所有 SKU
        $product->setRelation('skus', $product->skus->keyBy('properties'));

        // 获取数据库中所有的SKU
        $allSku = [];
        if ($product->exists === true) {
            /**
             * @var Collection|array|Product[] $all
             */
            $allSku = $product->skus()->withTrashed()->get()->keyBy('properties');
        }


        if ($product->is_multiple_spec === false) {
            $skuData                 = new ProductSkuData();
            $skuData->properties     = '';
            $skuData->propertiesName = null;
            $sku                     = $allSku[$skuData->properties] ?? $product->skus[$skuData->properties] ?? new ProductSku();

            $data   = $productData->toArray();
            $data   = Arr::only($data, [ 'image', 'barcode', 'outer_id', 'stock', 'price', 'market_price', 'cost_price' ]);
            $fields = [ 'status', 'image', 'barcode', 'outer_id', 'stock', 'price', 'market_price', 'cost_price', 'sales' ];
            foreach ($fields as $field) {
                $skuData->{$field} = $data[$field] ?? ($sku->{$field} ?? $product->{$field});
            }

            $this->fillSku($sku, $skuData);
            $product->skus[$skuData->properties] = $sku;
        }

        // 如果是多规格
        if ($product->is_multiple_spec === true) {

            $productData->skus?->each(function ($skuData) use ($product, $allSku) {
                /**
                 * @var $skuData ProductSkuData
                 */
                $skuData->properties = $this->propertyFormatter->formatString($skuData->properties);
                $sku                 = $allSku[$skuData->properties] ?? $product->skus[$skuData->properties] ?? new ProductSku();
                $this->fillSku($sku, $skuData);
                $product->skus[$skuData->properties] = $sku;
            });
            if ($productData->skus) {
                $newSkus = collect($productData->skus)->pluck('properties')->values()->toArray();
                $product->skus->pluck('properties')->each(function ($properties) use ($product, $newSkus) {
                    if (!in_array($properties, $newSkus, true)) {
                        unset($product->skus[$properties]);
                    }
                });
            }
        }
    }

    protected function fillSku(ProductSku $sku, ProductSkuData $productSkuData) : void
    {
        $sku->properties      = $productSkuData->properties;
        $sku->properties_name = $productSkuData->propertiesName;
        $sku->image           = $productSkuData->image;
        $sku->barcode         = $productSkuData->barcode;
        $sku->outer_id        = $productSkuData->outerId;
        $sku->properties      = $productSkuData->properties;
        $sku->price           = $productSkuData->price;
        $sku->stock           = $productSkuData->stock;
        $sku->safety_stock    = $productSkuData->safetyStock;
        $sku->virtual_stock   = $productSkuData->virtualStock;
        $sku->market_price    = $productSkuData->marketPrice;
        $sku->cost_price      = $productSkuData->costPrice;
        $sku->status          = $productSkuData->status ?? ProductStatusEnum::ON_SALE;

    }

}
