<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Enums\Product\FreightPayerEnum;
use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Product\Enums\Product\ProductTypeEnum;
use RedJasmine\Product\Enums\Product\ShippingTypeEnum;
use RedJasmine\Product\Enums\Product\SubStockTypeEnum;
use RedJasmine\Support\Enums\BoolIntEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;
use RedJasmine\Support\Traits\Models\HasOwner;
use RedJasmine\Support\Traits\Models\WithDTO;


class Product extends Model
{
    use WithDTO;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOwner;

    use HasOperator;

    public $incrementing = false;


    protected $casts = [
        'product_type'     => ProductTypeEnum::class,  // 商品类型
        'shipping_type'    => ShippingTypeEnum::class,// 发货类型
        'status'           => ProductStatusEnum::class,// 状态
        'sub_stock'        => SubStockTypeEnum::class,// 扣库存方式
        'freight_payer'    => FreightPayerEnum::class,// 运费承担方
        'is_multiple_spec' => BoolIntEnum::class,
        'off_sale_time'    => 'datetime',
        'on_sale_time'     => 'datetime',
        'sold_out_time'    => 'datetime',
        'modified_time'    => 'datetime',
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
        return $this->hasMany(ProductSku::class, 'product_id', 'id');
    }

    protected function marketPrice() : Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => $value,
            set: fn($value) => trim($value) === '' ? null : $value,
        );
    }

    protected function costPrice() : Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => $value,
            set: fn($value) => trim($value) === '' ? null : $value,
        );
    }

    protected function min() : Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => $value,
            set: fn($value) => trim($value) === '' ? null : $value,
        );
    }

    protected function max() : Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => $value,
            set: fn($value) => trim($value) === '' ? null : $value,
        );
    }

}
