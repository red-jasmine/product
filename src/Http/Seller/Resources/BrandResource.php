<?php

namespace RedJasmine\Product\Http\Seller\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Product\Models\Brand;
use RedJasmine\Support\Http\Resources\WithCollectionResource;


/**
 * @mixin Brand
 */
class BrandResource extends JsonResource
{

    use WithCollectionResource;

    public function toArray(Request $request) : array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'english_name' => $this->english_name,
            'is_show'      => $this->is_show,
            'logo'         => $this->logo,
            'status'       => $this->status,
            'extends'      => $this->extends,
        ];
    }
}
