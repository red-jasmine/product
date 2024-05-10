<?php

namespace RedJasmine\Product\Application\Brand\Services\Handlers;

use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;
use RedJasmine\Product\Doamin\Brand\Repositories\BrandRepositoryInterface;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Support\Application\CommandHandler;

class BrandCreateCommandHandler extends CommandHandler
{

    public ?Brand $aggregate = null;

    public function __construct(protected BrandRepositoryInterface $repository)
    {
        parent::__construct();
    }


    public function handle(BrandCreateCommand $command) : int
    {

        $brand               = new Brand();
        $brand->creator      = $this->getOperator();
        $brand->parent_id    = $command->parentId;
        $brand->name         = $command->name;
        $brand->english_name = $command->englishName;
        $brand->initial      = $command->initial;
        $brand->logo         = $command->logo;
        $brand->sort         = $command->sort;
        $brand->is_show      = $command->is_show;
        $brand->status       = $command->status;
        $brand->extends      = $command->extends;


        $this->execute(
            execute: fn() => $brand->create(),
            persistence: fn() => $this->repository->store($brand),
        );

        return $brand->id;

    }

}
