<?php

namespace RedJasmine\Product\Application\Stock\Services;

use Exception;
use RedJasmine\Product\Application\Stock\UserCases\StockInitCommand;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuRepositoryInterface;
use RedJasmine\Product\Domain\Stock\StockDomainService;
use RedJasmine\Product\Exceptions\ProductStockException;
use RedJasmine\Support\Application\ApplicationCommandService;

class StockCommandService extends ApplicationCommandService
{
    public function __construct(
        protected ProductSkuRepositoryInterface $repository,
        protected StockDomainService            $domainService
    )
    {
        parent::__construct();
    }


    protected static $macros = [];

    /**
     * 验证库存
     *
     * @param int $quantity
     *
     * @return int
     * @throws ProductStockException
     */
    public function validateQuantity(int $quantity) : int
    {
        // 核心操作 $quantity 都为 正整数
        if (bccomp($quantity, 0, 0) < 0) {
            throw new ProductStockException('操作库存 数量必须大于 0');
        }
        return $quantity;
    }


    /**
     * @param StockInitCommand $command
     *
     * @return void
     * @throws ProductStockException
     * @throws Exception
     */
    public function init(StockInitCommand $command) : void
    {
        $command->stock = $this->validateQuantity($command->stock);
        $this->domainService->init($command->skuId, $command->productId, $command->stock);

        $log                = new ProductStockLog();
        $log->product_id    = $command->productId;
        $log->sku_id        = $command->skuId;
        $log->change_type   = $command->changeType;
        $log->change_detail = $command->changeDetail;
        $log->channel_type  = $command->channelType;
        $log->channel_id    = $command->channelId;
        $log->type          = ProductStockTypeEnum::INIT;
        $log->stock         = $command->stock;
        $log->lock_stock    = 0;
        $log->creator       = $this->getOperator();

        $this->log($log);

    }

    /**
     * @param ProductStockLog $log
     *
     * @return void
     * @throws Exception
     */
    protected function log(ProductStockLog $log) : void
    {
        $log->id = $this->buildId();
        $this->repository->log($log);
    }


}
