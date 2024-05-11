<?php

namespace RedJasmine\Product\Application\Brand\Services\Handlers;

use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandUpdateCommand;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;
use RedJasmine\Support\Application\CommandHandler;

class BrandUpdateCommandHandler extends CommandHandler
{

    public ?Brand $aggregate = null;

    public function __construct(protected BrandRepositoryInterface $repository)
    {
        parent::__construct();
    }


    public function handle(BrandUpdateCommand $command) : void
    {

        $brand = $this->repository->find($command->id);
        $brand->setOperator($this->getOperator());

        $brand->parent_id    = $command->parentId;
        $brand->name         = $command->name;
        $brand->english_name = $command->englishName;
        $brand->initial      = $command->initial;
        $brand->logo         = $command->logo;
        $brand->sort         = $command->sort;
        $brand->is_show      = $command->isShow;
        $brand->status       = $command->status;
        $brand->extends      = $command->extends;

        $this->execute(
            execute: fn() => $brand->modify(),
            persistence: fn() => $this->repository->update($brand),
        );


    }

}
