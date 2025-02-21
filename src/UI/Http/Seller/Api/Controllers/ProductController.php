<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Product\Services\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductDeleteCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductUpdateCommand;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Product\UI\Http\Seller\Api\Resources\ProductResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProductController extends Controller
{

    public function __construct(
        protected ProductCommandService $commandService,
        protected ProductQueryService   $queryService,

    )
    {
        $this->queryService->getRepository()->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }


    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->queryService->paginate(PaginateQuery::from($request->all()));

        return ProductResource::collection($result->appends($request->query()));
    }

    public function show($id, Request $request) : ProductResource
    {
        $result = $this->queryService->findById(FindQuery::make($id, $request));
        return ProductResource::make($result);
    }


    public function store(Request $request) : ProductResource
    {
        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());

        $command = ProductCreateCommand::from($request);

        $result = $this->commandService->create($command);

        return ProductResource::make($result);
    }


    public function update(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $this->queryService->findById(FindQuery::make($id));
        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());

        $command = ProductUpdateCommand::from($request);

        $this->commandService->update($command);

        return static::success();

    }

    public function destroy($id, Request $request) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $this->queryService->findById(FindQuery::make($id));
        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());

        $command = ProductDeleteCommand::from($request);

        $this->commandService->delete($command);

        return static::success();
    }
}
