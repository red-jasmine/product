<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Property\Services\ProductPropertyGroupCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyGroupQueryService;
use RedJasmine\Product\Application\Property\UserCases\Queries\PropertyGroupPaginateQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\PropertyGroupResource;

class PropertyGroupController
{
    public function __construct(
        protected ProductPropertyGroupCommandService $commandService,
        protected ProductPropertyGroupQueryService   $queryService,

    )
    {
    }


    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->queryService->paginate(PropertyGroupPaginateQuery::from($request));
        return PropertyGroupResource::collection($result);

    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
