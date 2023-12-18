<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Product\Enums\Property\PropertyStatusEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\WithOperatorModel;

class ProductProperty extends Model
{

    use HasDateTimeFormatter;

    use WithOperatorModel;


    protected $primaryKey = 'pid';


    public $incrementing = false;

    protected $fillable = [
        'pid',
        'name',
        'status',
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


    public function values() : HasMany
    {
        return $this->hasMany(ProductPropertyValue::class, 'pid', 'pid');
    }
}
