<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Property\Services\ProductPropertyCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyQueryService;
use RedJasmine\Product\Application\Property\UserCases\Queries\PropertyPaginateQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\PropertyResource;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;

class PropertyController
{
    public function __construct(
        protected ProductPropertyCommandService $commandService,
        protected ProductPropertyQueryService   $queryService,

    )
    {
    }


    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->queryService->paginate(PropertyPaginateQuery::from($request));

        return PropertyResource::collection($result);
    }

    public function store(Request $request)
    {
    }

    public function show($id, Request $request) : PropertyResource
    {
        $result = $this->queryService->find($id, FindQuery::from($request));

        return PropertyResource::make($result);
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
