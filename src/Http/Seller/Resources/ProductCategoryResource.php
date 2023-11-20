<?php

namespace RedJasmine\Product\Http\Seller\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Product\Models\ProductCategory;
use RedJasmine\Support\Http\Resources\WithCollectionResource;

/**
 * @mixin ProductCategory
 */
class ProductCategoryResource extends JsonResource
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
            'extends'    => $this->extends,
        ];
    }
}
