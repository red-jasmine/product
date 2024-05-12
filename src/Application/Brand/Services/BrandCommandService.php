<?php

namespace RedJasmine\Product\Application\Brand\Services;

use RedJasmine\Product\Application\Brand\Services\Handlers\BrandCreateCommandHandler;
use RedJasmine\Product\Application\Brand\Services\Handlers\BrandDeleteCommandHandler;
use RedJasmine\Product\Application\Brand\Services\Handlers\BrandUpdateCommandHandler;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandDeleteCommand;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandUpdateCommand;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * @method int create(BrandCreateCommand $command)
 * @method void update(BrandUpdateCommand $command)
 * @method void delete(BrandDeleteCommand $command)
 */
class BrandCommandService extends ApplicationCommandService
{

    /**
     * 定义模型
     * @var string
     */
    protected static string $modelClass = Brand::class;

    /**
     * 仓库
     *
     * @param BrandRepositoryInterface $repository
     */
    public function __construct(protected BrandRepositoryInterface $repository)
    {
        parent::__construct();
    }


}
