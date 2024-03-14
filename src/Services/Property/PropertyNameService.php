<?php

namespace RedJasmine\Product\Services\Property;

use Exception;
use RedJasmine\Product\Models\ProductProperty;
use RedJasmine\Product\Services\Property\Actions\Names;
use RedJasmine\Product\Services\Property\Data\PropertyData;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Helpers\ID\Snowflake;

/**
 * @method ProductProperty create(PropertyData $data)
 * @method ProductProperty update(int $id, PropertyData $data)
 */
class PropertyNameService extends Service
{

    /**
     * 默认的操作
     * @var array|string[]
     */
    protected static array $actions = [
        'create' => Names\PropertyCreateAction::class,
        'update' => Names\PropertyUpdateAction::class,
    ];


    /**
     * @return int
     * @throws Exception
     */
    public function buildID() : int
    {
        return Snowflake::getInstance()->nextId();
    }


}
