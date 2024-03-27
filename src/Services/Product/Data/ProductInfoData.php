<?php

namespace RedJasmine\Product\Services\Product\Data;

use Illuminate\Support\Collection;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Helpers\Json\Json;
use Spatie\LaravelData\DataCollection;


class ProductInfoData extends Data
{


    public ?string $description = null;
    public ?string $keywords    = null;
    public ?string $detail      = null;
    public ?array  $images      = null;
    public ?array  $videos      = null;
    public ?string $weight;
    public ?string $width;
    public ?string $height;
    public ?string $length;
    public ?string $size;
    public ?string $remarks;
    public ?array  $tools;
    public ?array  $extends;


    /**
     * @var Collection<int,ProductPropData>|null
     */
    public ?Collection $basicProps = null;
    /**
     * @var Collection<int,ProductPropData>|null
     */
    public ?Collection $saleProps = null;

    public static function prepareForPipeline(array $properties) : array
    {

        if (isset($properties['basic_props'])) {
            $properties['basic_props'] = Json::toArray($properties['basic_props']);
        }
        if (isset($properties['sale_props'])) {
            $properties['sale_props'] = Json::toArray($properties['sale_props']);
        }

        return $properties;
    }


}
