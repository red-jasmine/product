<?php

namespace RedJasmine\Product\Domain\Property\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class ProductProperty extends Model
{

    use HasDateTimeFormatter;

    use HasOperator;


    protected $primaryKey = 'id';


    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'status',
        'group_id', // TODO 验证
        'expands',
        'sort',
        'creator_type',
        'creator_id',
    ];

    protected $casts = [
        'expands' => 'array',
        'status'      => PropertyStatusEnum::class
    ];

    public function scopeAvailable(Builder $query) : Builder
    {
        return $query->where('status', PropertyStatusEnum::ENABLE);
    }

    public function group() : BelongsTo
    {
        return $this->belongsTo(ProductPropertyGroup::class, 'group_id', 'id');
    }

    public function values() : HasMany
    {
        return $this->hasMany(ProductPropertyValue::class, 'id', 'pid');
    }
}