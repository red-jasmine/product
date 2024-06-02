<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueQueryService;
use RedJasmine\Product\Application\Property\UserCases\Queries\PropertyValuePaginateQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\PropertyValueResource;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;

class PropertyValueController
{
    public function __construct(
        protected ProductPropertyValueCommandService $commandService,
        protected ProductPropertyValueQueryService   $queryService,

    )
    {
    }


    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->queryService->paginate(PropertyValuePaginateQuery::from($request));

        return PropertyValueResource::collection($result);

    }

    public function store(Request $request)
    {
    }

    public function show($id, Request $request) : PropertyValueResource
    {
        $result = $this->queryService->find($id, FindQuery::from($request));

        return PropertyValueResource::make($result);
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
