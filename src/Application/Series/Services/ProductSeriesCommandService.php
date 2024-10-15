<?php

namespace RedJasmine\Product\Application\Series\Services;

use RedJasmine\Product\Application\Series\Services\CommandHandlers\ProductSeriesCreateCommandHandler;
use RedJasmine\Product\Application\Series\Services\CommandHandlers\ProductSeriesDeleteCommandHandler;
use RedJasmine\Product\Application\Series\Services\CommandHandlers\ProductSeriesUpdateCommandHandler;
use RedJasmine\Product\Application\Series\Services\Pipelines\SeriesProductPipeline;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;

class ProductSeriesCommandService extends ApplicationCommandService
{


    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix  = 'product.application.series.command';

    protected static string $modelClass = ProductSeries::class;

    protected static $macros = [
        'create' => ProductSeriesCreateCommandHandler::class,
        'update' => ProductSeriesUpdateCommandHandler::class,
        'delete' => ProductSeriesDeleteCommandHandler::class
    ];

    public function __construct(
        protected ProductSeriesRepositoryInterface $repository
    )
    {

    }


    protected function hooks() : array
    {
        return [
            'create' => [
                SeriesProductPipeline::class,
            ],
            'update' => [
                SeriesProductPipeline::class,
            ]
        ];
    }

}
