<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Category\Models\ProductCategory */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'image'        => $this->image,
            'group_name'   => $this->group_name,
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
