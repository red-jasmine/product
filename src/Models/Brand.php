<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Enums\Brand\BrandStatusEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;


class Brand extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;


    public $incrementing = false;

    protected $casts = [
        'extends' => 'array',
        'status'  => BrandStatusEnum::class
    ];


    protected $fillable = [
        'id',
        'name',
        'status',
        'extends',
        'logo',
        'logo',
    ];



}
