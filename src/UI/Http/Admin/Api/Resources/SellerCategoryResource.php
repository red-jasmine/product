<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Category\Models\ProductSellerCategory */
class SellerCategoryResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'           => $this->id,
            'owner_id'     => $this->owner_id,
            'owner_type'   => $this->owner_type,
            'name'         => $this->name,
            'group_name'   => $this->group_name,
            'image'        => $this->image,
            'sort'         => $this->sort,
            'is_leaf'      => $this->is_leaf,
            'is_show'      => $this->is_show,
            'status'       => $this->status,
            'expands'      => $this->expands,
            'creator_id'   => $this->creator_id,
            'creator_type' => $this->creator_type,
            'updater_id'   => $this->updater_id,
            'updater_type' => $this->updater_type,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
            'children'     => $this->children,

            'parent_id' => $this->parent_id,

            'parent' => new static($this->whenLoaded('parent')),
        ];
    }
}
