<?php

namespace RedJasmine\Product\Application\Brand\Services;

use RedJasmine\Product\Application\Brand\Services\Handlers\BrandCreateCommandHandler;
use RedJasmine\Product\Application\Brand\Services\Handlers\BrandUpdateCommandHandler;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * @method int create(BrandCreateCommand $command)
 */
class BrandCommandService extends ApplicationCommandService
{

    protected static $macros = [
        'create' => BrandCreateCommandHandler::class,
        'update' => BrandUpdateCommandHandler::class,
    ];

}
