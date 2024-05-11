<?php

namespace RedJasmine\Support\Tests\Application\Brand\CommandHandlers;

use Illuminate\Support\Str;
use RedJasmine\Product\Application\Brand\Services\BrandCommandService;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;
use RedJasmine\Product\Domain\Brand\Models\Enums\BrandStatusEnum;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;


class BrandCreateCommandHandlerTest extends BrandTestCase
{
    
    /**
     * 测试能创建品牌
     * 前提条件: 模拟数据
     * 步骤：
     *  1、创建
     *  2、
     *  3、
     * 预期结果:
     *  1、
     *  2、
     * @return void
     */
    public function test_can_create_brand() : void
    {
        $command = BrandCreateCommand::from([
                                                'parent_id'    => 0,
                                                'sort'         => 0,
                                                'name'         => fake()->name,
                                                'english_name' => fake('en')->name,
                                                'logo'         => fake()->imageUrl(200, 200),
                                                'initial'      => Str::upper(fake()->randomLetter()),
                                                'status'       => fake()->randomElement(BrandStatusEnum::values()),
                                                'extends'      => null,
                                                'is_show'      => true,
                                            ]);


        $brandId = $this->brandCommandService()->create($command);


        $brand = $this->brandRepository()->find($brandId);


        $this->assertEquals($command->name, $brand->name);
        $this->assertEquals($command->englishName, $brand->english_name);
        $this->assertEquals($command->sort, $brand->sort);
        $this->assertEquals($command->logo, $brand->logo);
        $this->assertEquals($command->isShow, $brand->is_show);
        $this->assertEquals($command->parentId, $brand->parent_id);
        $this->assertEquals($command->status->value, $brand->status->value);

        $this->assertEquals($this->user()->getType(), $brand->creator->getType());
        $this->assertEquals($this->user()->getID(), $brand->creator->getID());

    }

}
