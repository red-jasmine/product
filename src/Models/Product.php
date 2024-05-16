<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Product\Domain\Category\Models\ProductSellerCategory;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Models\ProductSeriesProduct;
use RedJasmine\Product\Services\Product\Enums\FreightPayerEnum;
use RedJasmine\Product\Services\Product\Enums\ProductStatusEnum;
use RedJasmine\Product\Services\Product\Enums\ProductTypeEnum;
use RedJasmine\Product\Services\Product\Enums\ShippingTypeEnum;
use RedJasmine\Product\Services\Product\Enums\SubStockTypeEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;
use RedJasmine\Support\Traits\Models\HasOwner;


class Product extends Model
{
    use HasDateTimeFormatter;

    use HasOwner;

    use HasOperator;

    use SoftDeletes;

    public $incrementing = false;


    protected $casts = [
        'product_type'     => ProductTypeEnum::class,  // 商品类型
        'shipping_type'    => ShippingTypeEnum::class,// 发货类型
        'status'           => ProductStatusEnum::class,// 状态
        'sub_stock'        => SubStockTypeEnum::class,// 扣库存方式
        'freight_payer'    => FreightPayerEnum::class,// 运费承担方
        'is_multiple_spec' => 'boolean',
        'off_sale_time'    => 'datetime',
        'on_sale_time'     => 'datetime',
        'sold_out_time'    => 'datetime',
        'modified_time'    => 'datetime',
        'is_hot'           => 'boolean',
        'is_new'           => 'boolean',
        'is_best'          => 'boolean',
        'is_benefit'       => 'boolean',
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


    /**
     * 系列
     * @return HasOneThrough
     */
    public function series() : HasOneThrough
    {
        return $this->hasOneThrough(
            ProductSeries::class,
            ProductSeriesProduct::class,
            'product_id',
            'id',
            'id',
            'series_id'
        )->with([ 'products' ]);
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
