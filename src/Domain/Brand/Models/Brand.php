<?php

namespace RedJasmine\Product\Domain\Brand\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Domain\Brand\Models\Enums\BrandStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Foundation\HasServiceContext;
use RedJasmine\Support\Helpers\ID\Snowflake;
use RedJasmine\Support\Traits\HasDateTimeFormatter;

use RedJasmine\Support\Traits\Models\ModelTree;


class Brand extends Model
{

    use HasServiceContext;

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
        'expands' => 'array',
        'is_show' => 'boolean',
        'status'  => BrandStatusEnum::class
    ];


    protected $fillable = [
        'id',
        'parent_id',
        'name',
        'english_name',
        'initial',
        'is_show',
        'status',
        'expands',
        'logo',
        'sort',
    ];


    public function create() : static
    {
        $this->id      = Snowflake::getInstance()->nextId();
        $this->creator = $this->getOperator();
        return $this;
    }

    public function modify() : static
    {
        $this->updater = $this->getOperator();
        return $this;
    }

    public function isAllowUse() : bool
    {
        return $this->status === BrandStatusEnum::ENABLE;
    }

}
