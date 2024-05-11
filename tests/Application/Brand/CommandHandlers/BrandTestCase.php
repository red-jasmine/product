<?php

namespace RedJasmine\Support\Tests\Application\Brand\CommandHandlers;

use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use RedJasmine\Product\Application\Brand\Services\BrandCommandService;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;
use RedJasmine\Product\Domain\Brand\Models\Enums\BrandStatusEnum;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;
use RedJasmine\Product\Infrastructure\Repositories\Eloquent\BrandRepository;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;


class BrandTestCase extends ApplicationTestCase
{


    protected function brandCommandService() : BrandCommandService
    {
        return app(BrandCommandService::class)->setOperator($this->user());
    }


    protected function brandRepository() : BrandRepositoryInterface
    {
        return app(BrandRepositoryInterface::class);
    }


}
