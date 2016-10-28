<?php


namespace App\Http\Controllers\Api;


use App\JsonApi\Users\Hydrator;
use App\JsonApi\Users\Request;
use App\User;
use CloudCreativity\LaravelJsonApi\Http\Controllers\EloquentController;
use CloudCreativity\LaravelJsonApi\Search\SearchAll;

class UsersController extends EloquentController
{

    /**
     * UsersController constructor.
     * @param Hydrator $hydrator
     */
    public function __construct(Hydrator $hydrator, SearchAll $search)
    {
        parent::__construct(new User(), $hydrator, $search);
    }

    /**
     * @inheritdoc
     */
    protected function getRequestHandler()
    {
        return Request::class;
    }
}