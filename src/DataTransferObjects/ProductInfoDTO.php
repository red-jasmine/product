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
     * @var DataCollection<ProductPropDTO>|null
     */
    public ?DataCollection $basicProps = null;
    /**
     * @var DataCollection<ProductPropDTO>|null
     */
    public ?DataCollection $saleProps = null;

    public static function prepareForPipeline(Collection $properties) : Collection
    {

        if ($properties->offsetExists('basic_props')) {
            $properties->put('basic_props', Json::toArray($properties->get('basic_props', '')));
        }
        if ($properties->offsetExists('sale_props')) {
            $properties->put('sale_props', Json::toArray($properties->get('sale_props', '')));

        }
        return $properties;
    }


}
