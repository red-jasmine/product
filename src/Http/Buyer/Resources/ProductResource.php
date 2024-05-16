<?php

namespace RedJasmine\Product\Http\Buyer\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Product\Domain\Product\Models\Product;
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
                'description' => $this->info->description,
                'detail'      => $this->info->detail,
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

        $series = null;
        if ($this->relationLoaded('series')) {
            $series = new ProductSeriesResource($this->series);
        }

        $skus = null;
        if ($this->relationLoaded('skus')) {
            $skus = ProductSkuResource::collection($this->skus);
        }

        $brand = null;
        if ($this->relationLoaded('brand')) {
            $brand = new BrandResource($this->brand);
        }
        $category = null;
        if ($this->relationLoaded('category')) {
            $category = new CategoryResource($this->category);
        }

        $sellerCategory = null;
        if ($this->relationLoaded('sellerCategory')) {
            $sellerCategory = new SellerCategoryResource($this->sellerCategory);
        }


        return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'owner_type'         => $this->owner_type,
            'owner_id'           => $this->owner_id,
            'product_type'       => $this->product_type,
            'shipping_type'      => $this->shipping_type,
            'image'              => $this->image,
            'barcode'            => $this->barcode,
            'is_multiple_spec'   => $this->is_multiple_spec,
            'status'             => $this->status,
            'price'              => $this->price,
            'market_price'       => $this->market_price,
            'brand_id'           => $this->brand_id,
            'category_id'        => $this->category_id,
            'seller_category_id' => $this->seller_category_id,
            'freight_payer'      => $this->freight_payer,
            'postage_id'         => $this->postage_id,
            'min'                => $this->min,
            'max'                => $this->max,
            'multiple'           => $this->multiple,
            'sub_stock'          => $this->sub_stock,
            'stock'              => $this->stock,
            'lock_stock'         => $this->lock_stock,
            'sales'              => $this->sales,
            'delivery_time'      => $this->delivery_time,
            'vip'                => $this->vip,
            'points'             => $this->points,
            'is_hot'             => $this->is_hot,
            'is_new'             => $this->is_new,
            'is_best'            => $this->is_best,
            'is_benefit'         => $this->is_benefit,
            'info'               => $info,
            'skus'               => $skus,
            $this->mergeWhen($this->relationLoaded('series'), [ 'series' => $series ]),
            $this->mergeWhen($this->relationLoaded('brand'), [ 'brand' => $brand ]),
            $this->mergeWhen($this->relationLoaded('category'), [ 'category' => $category ]),
            $this->mergeWhen($this->relationLoaded('sellerCategory'), [ 'seller_category' => $sellerCategory ]),

        ];
    }
}
