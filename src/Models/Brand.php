<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Services\Brand\Enums\BrandStatusEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;
use RedJasmine\Support\Traits\Models\ModelTree;


class Brand extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    use ModelTree;

    // 父级ID字段名称，默认值为 parent_id
    protected string $parentColumn = 'parent_id';
    // 排序字段名称，默认值为 order
    protected string $orderColumn = 'sort';

    // 标题字段名称，默认值为 title
    protected string $titleColumn = 'name';


    public $incrementing = false;

    protected $casts = [
        'extends' => 'array',
        'is_show' => 'boolean',
        'status'  => BrandStatusEnum::class
    ];


    protected $fillable = [
        'id',
        'parent_id',
        'name',
        'english_name',
        'is_show',
        'status',
        'extends',
        'logo',
        'sort',
    ];


}
