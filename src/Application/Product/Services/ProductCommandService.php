<?php

namespace RedJasmine\Product\Application\Product\Services;

use RedJasmine\Product\Application\Product\Services\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductCreateCommandHandler;
use RedJasmine\Product\Application\Product\Services\Commands\ProductDeleteCommandHandler;
use RedJasmine\Product\Application\Product\Services\Commands\ProductSetStatusCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductSetStatusCommandHandler;
use RedJasmine\Product\Application\Product\Services\Commands\ProductUpdateCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductUpdateCommandHandler;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Repositories\ProductRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;


/**
 * @see ProductCreateCommandHandler::handle()
 * @method Product create(ProductCreateCommand $command)
 * @see ProductUpdateCommandHandler::handle()
 * @method void update(ProductUpdateCommand $command)
 * @method void setStatus(ProductSetStatusCommand $command)
 */
class ProductCommandService extends ApplicationCommandService
{

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.product.command';


    protected static string $modelClass = Product::class;
    protected static        $macros     = [
        'create'    => ProductCreateCommandHandler::class,
        'update'    => ProductUpdateCommandHandler::class,
        'delete'    => ProductDeleteCommandHandler::class,
        'setStatus' => ProductSetStatusCommandHandler::class,
    ];

    public function __construct(
        public ProductRepositoryInterface $repository
    )
    {

    }



}
