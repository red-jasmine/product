<?php

namespace RedJasmine\Product\Application\Property\Services\Pipelines;

use Closure;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Application\CommandHandlerPipeline;

class ProductPropertyPipeline extends CommandHandlerPipeline
{
    public function executing(CommandHandler $handler, Closure $next)
    {
        // 获取入参

        return $next($handler);

    }


}
