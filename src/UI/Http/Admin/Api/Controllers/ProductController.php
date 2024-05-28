<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductCreateCommand;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\ProductResource;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;
use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class ProductController extends Controller
{

    public function __construct(
        protected ProductCommandService $commandService,
        protected ProductQueryService   $queryService,

    )
    {
    }


    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->queryService->paginate(PaginateQuery::from($request));
        return ProductResource::collection($result->appends($request->query()));
    }

    public function store(Request $request) : ProductResource
    {
        $command = ProductCreateCommand::validateAndCreate($request);
        $result  = $this->commandService->create($command);
        return ProductResource::make($result);
    }

    public function show($id, Request $request) : ProductResource
    {
        $result = $this->queryService->find($id, FindQuery::from($request));
        return ProductResource::make($result);
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
