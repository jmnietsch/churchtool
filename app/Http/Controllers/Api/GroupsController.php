<?php


namespace App\Http\Controllers\Api;


use App\Group;
use App\JsonApi\Groups\Hydrator;
use App\JsonApi\Groups\Request;
use CloudCreativity\LaravelJsonApi\Http\Controllers\EloquentController;
use CloudCreativity\LaravelJsonApi\Search\SearchAll;

class GroupsController extends EloquentController
{

    /**
     * UsersController constructor.
     * @param Hydrator $hydrator
     */
    public function __construct(Hydrator $hydrator, SearchAll $search)
    {
        parent::__construct(new Group(), $hydrator, $search);
    }

    /**
     * @inheritdoc
     */
    protected function getRequestHandler()
    {
        return Request::class;
    }
}