<?php

namespace RedJasmine\Product\Http\Seller\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Product\Models\Product;
use RedJasmine\Support\Http\Resources\WithCollectionResource;

/**
 * @mixin  Product
 */
class ProductResource extends JsonResource
{
    use WithCollectionResource;

    public function toArray(Request $request) : array
    {
        $info = null;

        if ($this->relationLoaded('info')) {
            $info = [
                'desc'        => $this->info->desc,
                'web_detail'  => $this->info->web_detail,
                'wap_detail'  => $this->info->wap_detail,
                'images'      => $this->info->images,
                'videos'      => $this->info->videos,
                'weight'      => $this->info->weight,
                'width'       => $this->info->width,
                'height'      => $this->info->height,
                'length'      => $this->info->length,
                'size'        => $this->info->size,
                'basic_props' => $this->info->basic_props,
                'sale_props'  => $this->info->sale_props,
                'remarks'     => $this->info->remarks,
                'tools'       => $this->info->tools,
                'extends'     => $this->info->extends,
            ];
        }
        $skus = null;

        if ($this->relationLoaded('skus')) {
            $skus = static::collection($this->skus);
        }

        $brand = null;
        if ($this->relationLoaded('brand')) {
            $brand = new BrandResource($this->brand);
        }
        $category = null;
        if ($this->relationLoaded('category')) {
            $category = new ProductCategoryResource($this->category);
        }

        $sellerCategory = null;
        if ($this->relationLoaded('sellerCategory')) {
            $sellerCategory = new ProductSellerCategoryResource($this->sellerCategory);
        }


        return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'owner_type'         => $this->owner_type,
            'owner_uid'          => $this->owner_uid,
            'product_type'       => $this->product_type,
            'shipping_type'      => $this->shipping_type,
            'image'              => $this->image,
            'barcode'            => $this->barcode,
            'outer_id'           => $this->outer_id,
            'keywords'           => $this->keywords,
            'parent_id'          => $this->parent_id,
            'has_skus'           => $this->has_skus,
            'is_sku'             => $this->is_sku,
            'status'             => $this->status,
            'price'              => $this->price,
            'market_price'       => $this->market_price,
            'cost_price'         => $this->cost_price,
            'brand_id'           => $this->brand_id,
            'category_id'        => $this->category_id,
            'seller_category_id' => $this->seller_category_id,
            'properties'         => $this->properties,
            'properties_name'    => $this->properties_name,
            'freight_payer'      => $this->freight_payer,
            'postage_id'         => $this->postage_id,
            'min'                => $this->min,
            'max'                => $this->max,
            'multiple'           => $this->multiple,
            'sub_stock'          => $this->sub_stock,
            'quantity'           => $this->quantity,
            'hold_quantity'      => $this->hold_quantity,
            'sold_quantity'      => $this->sold_quantity,
            'delivery_time'      => $this->delivery_time,
            'vip'                => $this->vip,
            'points'             => $this->points,
            'on_sale_time'       => $this->on_sale_time?->toDateTimeString(),
            'sold_out_time'      => $this->sold_out_time?->toDateTimeString(),
            'off_sale_time'      => $this->off_sale_time?->toDateTimeString(),
            'modified_time'      => $this->modified_time?->toDateTimeString(),
            'creator_type'       => $this->creator_type,
            'creator_uid'        => $this->creator_uid,
            'updater_type'       => $this->updater_type,
            'updater_uid'        => $this->updater_uid,
            'created_at'         => $this->created_at?->toDateTimeString(),
            'updated_at'         => $this->updated_at?->toDateTimeString(),
            'deleted_at'         => $this->deleted_at?->toDateTimeString(),
            'info'               => $info,
            'skus'               => $skus,
            $this->mergeWhen($this->relationLoaded('brand'), [ 'brand' => $brand ]),
            $this->mergeWhen($this->relationLoaded('category'), [ 'category' => $category ]),
            $this->mergeWhen($this->relationLoaded('sellerCategory'), [ 'seller_category' => $sellerCategory ]),

        ];
    }
}
