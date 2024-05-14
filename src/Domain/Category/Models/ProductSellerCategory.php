<?php

namespace RedJasmine\Product\Domain\Category\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Product\Domain\Category\Enums\CategoryStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOwner;
use RedJasmine\Support\Traits\Models\ModelTree;

class ProductSellerCategory extends Model
{
    use HasDateTimeFormatter;

    use HasOwner;

    use HasOperator;

    use ModelTree;

    public $incrementing = false;

    // 父级ID字段名称，默认值为 parent_id
    protected string $parentColumn = 'parent_id';

    // 排序字段名称，默认值为 order
    protected string $orderColumn = 'sort';

    // 标题字段名称，默认值为 title
    protected string $titleColumn = 'name';


    protected $fillable = [
        'parent_id',
        'name',
        'group_name',
        'image',
        'sort',
        'status',
        'is_leaf',
        'is_show',
        'extend_info',
    ];

    protected $casts = [
        'extend_info' => 'array',
        'status'      => CategoryStatusEnum::class,
        'is_leaf'     => 'boolean',
        'is_show'     => 'boolean',
    ];

    public function parent() : BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    /**
     * 叶子目录
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeLeaf(Builder $query) : Builder
    {
        return $query->where('is_leaf', true);
    }


    /**
     * @return bool
     */
    public function isAllowUse() : bool
    {
        return $this->is_leaf;
    }

}
