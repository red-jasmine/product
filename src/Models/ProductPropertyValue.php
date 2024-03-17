<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Product\Services\Property\Enums\PropertyStatusEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;

class ProductPropertyValue extends Model
{

    use HasDateTimeFormatter;

    use HasOperator;

    public $incrementing = false;

    protected $primaryKey = 'vid';

    public function scopeAvailable(Builder $query) : Builder
    {
        return $query->where('status', PropertyStatusEnum::ENABLE);
    }

    protected $fillable = [
        'vid',
        'pid',
        'name',
        'group_id',
        'status',
        'extends',
        'sort',
        'creator_type',
        'creator_id',
    ];


    protected $casts = [
        'extends' => 'array',
        'status'  => PropertyStatusEnum::class,
    ];


    public function property() : BelongsTo
    {
        return $this->belongsTo(ProductProperty::class, 'pid', 'pid');
    }
}
