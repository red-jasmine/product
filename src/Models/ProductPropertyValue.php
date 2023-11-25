<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Product\Enums\Property\PropertyStatusEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\WithOperatorModel;

class ProductPropertyValue extends Model
{

    use HasDateTimeFormatter;

    use WithOperatorModel;

    public $incrementing = false;

    public function scopeAvailable(Builder $query) : Builder
    {
        return $query->where('status', PropertyStatusEnum::ENABLE);
    }

    protected $fillable = [
        'vid',
        'pid',
        'name',
        'status',
        'extends',
        'sort',
        'creator_type',
        'creator_uid',
        'creator_nickname',

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
