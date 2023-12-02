<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Enums\Brand\BrandStatusEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\WithOperatorModel;


class Brand extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;

    use WithOperatorModel;


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
