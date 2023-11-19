<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Enums\Category\CategoryStatusEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\WithOperatorModel;

class ProductCategory extends Model
{
    use HasDateTimeFormatter;

    use WithOperatorModel;

    // 父级ID字段名称，默认值为 parent_id
    protected $parentColumn = 'parent_id';

    // 排序字段名称，默认值为 order
    protected $orderColumn = 'sort';

    // 标题字段名称，默认值为 title
    protected $titleColumn = 'name';


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
        'status'  => 'boolean',
        'status'  => CategoryStatusEnum::class,
    ];

    public function parent() : BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'cid');
    }

}
