<?php

namespace RedJasmine\Product\DataTransferObjects;

use Illuminate\Support\Collection;
use RedJasmine\Support\Helpers\Json\Json;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
class ProductInfoDTO extends Data
{


    public ?string $desc;
    public ?string $webDetail;
    public ?string $wapDetail;
    public ?array  $images = null;
    public ?array  $videos = null;
    public ?string $weight;
    public ?string $width;
    public ?string $height;
    public ?string $length;
    public ?string $remarks;
    public ?array  $tools;
    public ?array  $extends;
    public ?string $size;

    /**
     * @var DataCollection<ProductProp>|null
     */
    public ?DataCollection $basicProps = null;
    /**
     * @var DataCollection<ProductProp>|null
     */
    public ?DataCollection $saleProps = null;

    public static function prepareForPipeline(Collection $properties) : Collection
    {

        $properties->put('basic_props', Json::toArray($properties->get('basic_props', '')));
        $properties->put('sale_props', Json::toArray($properties->get('sale_props', '')));
        return $properties;
    }


}
