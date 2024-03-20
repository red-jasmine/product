<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use RedJasmine\Product\Services\Property\Enums\PropertyStatusEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;

class ProductProperty extends Model
{

    use HasDateTimeFormatter;

    use HasOperator;


    protected $primaryKey = 'pid';


    public $incrementing = false;

    protected $fillable = [
        'pid',
        'name',
        'status',
        'group_id',
        'extends',
        'sort',
        'creator_type',
        'creator_id',
    ];

    protected $casts = [
        'extends' => 'array',
        'status'  => PropertyStatusEnum::class
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
        return $this->hasMany(ProductPropertyValue::class, 'pid', 'pid');
    }
}
