<?php

namespace RedJasmine\Product\Http\Seller\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Product\Models\ProductCategory;
use RedJasmine\Product\Models\ProductSellerCategory;
use RedJasmine\Support\Http\Resources\WithCollectionResource;

/**
 * @mixin ProductSellerCategory
 */
class ProductSellerCategoryResource extends JsonResource
{
    use WithCollectionResource;

    public function toArray(Request $request) : array
    {
        return [
            'id'               => $this->id,
            'parent_id'        => $this->parent_id,
            'name'             => $this->name,
            'group_name'       => $this->group_name,
            'sort'             => $this->sort,
            'is_leaf'          => $this->is_leaf,
            'status'           => $this->status,
            'image'            => $this->image,
            'extends'          => $this->extends,
            'owner_type'       => $this->owner_type,
            'owner_uid'        => $this->owner_uid,
            'creator_type'     => $this->creator_type,
            'creator_uid'      => $this->creator_uid,
            'creator_nickname' => $this->creator_nickname,
            'updater_type'     => $this->updater_type,
            'updater_uid'      => $this->updater_uid,
            'updater_nickname' => $this->updater_nickname,
            'created_at'       => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'       => $this->updated_at?->format('Y-m-d H:i:s'),
            'children'         => $this->children ? static::collection(collect($this->children)) : null,
        ];
    }
}
