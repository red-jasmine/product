<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Services\Property\Enums\PropertyStatusEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;

class ProductPropertyGroup extends Model
{

    use HasDateTimeFormatter;

    use HasOperator;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'status',
        'extends',
        'sort',
    ];

    protected $casts = [
        'extends' => 'array',
        'status'  => PropertyStatusEnum::class
    ];

    public function scopeAvailable(Builder $query) : Builder
    {
        return $query->where('status', PropertyStatusEnum::ENABLE);
    }

}
