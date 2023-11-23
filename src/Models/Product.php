<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\WithOperatorModel;
use RedJasmine\Support\Traits\Models\WithOwnerModel;

class Product extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;

    use WithOwnerModel;

    use WithOperatorModel;


    /**
     * 类目
     * @return BelongsTo
     */
    public function category() : BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    /**
     *  附加信息
     * @return HasOne
     */
    public function info() : HasOne
    {
        return $this->hasOne(ProductInfo::class, 'id', 'id');
    }


    /**
     * 所有规格
     * @return HasMany
     */
    public function skus() : HasMany
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }
}
