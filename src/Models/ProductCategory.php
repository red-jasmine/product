<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Product\Enums\Category\CategoryStatusEnum;
use RedJasmine\Support\Enums\BoolIntEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\ModelTree;
use RedJasmine\Support\Traits\Models\HasOperator;
use RedJasmine\Support\Traits\Models\HasOwner;

class ProductCategory extends Model
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

        'sort',
        'is_leaf',
        'status',
        'extends',
    ];

    protected $casts = [
        'extends' => 'array',
        'status'  => CategoryStatusEnum::class,
        'is_leaf' => BoolIntEnum::class,
    ];

    public function parent() : BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id', 'cid');
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
        return $query->where('is_leaf', BoolIntEnum::YES);
    }

    /**
     * @return bool
     */
    public function isAllowUse() : bool
    {
        if ($this->is_leaf === BoolIntEnum::NO) {
            return false;
        }

        return true;
    }

}
