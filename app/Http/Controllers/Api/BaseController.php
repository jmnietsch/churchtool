<?php


namespace App\Http\Controllers\Api;


use CloudCreativity\JsonApi\Contracts\Http\Requests\RequestInterface as JsonApiRequest;
use CloudCreativity\LaravelJsonApi\Http\Controllers\EloquentController;

abstract class BaseController extends EloquentController
{

    /**
     * @param JsonApiRequest $request
     * @param string $type
     * @return array
     */
    protected function getIdsFromRelationshipRequest(JsonApiRequest $request, $type)
    {
        $ids = [];
        foreach ($request->getDocument()->getData() as $item) {
            if ($item->type == $type)
                $ids[] = $item->id;
        }

        return $ids;
    }

}