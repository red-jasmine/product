<?php

namespace RedJasmine\Product\Http\Buyer\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Support\Http\Resources\WithCollectionResource;

/**
 * @mixin ProductCategory
 */
class CategoryResource extends JsonResource
{
    use WithCollectionResource;

    public function toArray(Request $request) : array
    {
        return [

            'id'         => $this->id,
            'parent_id'  => $this->parent_id,
            'name'       => $this->name,
            'group_name' => $this->group_name,
            'sort'       => $this->sort,
            'is_leaf'    => $this->is_leaf,
            'status'     => $this->status,
            'expands'    => $this->expands,
            'children'   => $this->children ? static::collection(collect($this->children)) : null,
        ];
    }
}
