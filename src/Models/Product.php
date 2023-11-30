<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Enums\Brand\BrandStatusEnum;
use RedJasmine\Product\Enums\Product\ProductStatus;
use RedJasmine\Product\Enums\Product\ProductTypeEnum;
use RedJasmine\Product\Enums\Product\ShippingTypeEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\WithOperatorModel;
use RedJasmine\Support\Traits\Models\WithOwnerModel;

/**
 * @property string $owner_type
 * @property int    $owner_uid
 */
class Product extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;

    use WithOwnerModel;

    use WithOperatorModel;

    public $incrementing = false;


    protected $casts = [
        'product_type'  => ProductTypeEnum::class,  // 商品类型
        'shipping_type' => ShippingTypeEnum::class,// 发货类型
        'status'        => ProductStatus::class,// 状态
        'modified_time' => 'datetime',
        'off_sale_time' => 'datetime',
        'on_sale_time'  => 'datetime',
        'sold_out_time' => 'datetime',

    ];


    /**
     * 类目
     * @return BelongsTo
     */
    public function category() : BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }


    public function brand() : BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }


    public function sellerCategory() : BelongsTo
    {
        return $this->belongsTo(ProductSellerCategory::class, 'seller_category_id', 'id');
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
