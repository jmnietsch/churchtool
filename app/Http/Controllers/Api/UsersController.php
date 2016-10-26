<?php


namespace App\Http\Controllers\Api;


use App\JsonApi\Users\Hydrator;
use App\JsonApi\Users\Request;
use App\User;
use CloudCreativity\LaravelJsonApi\Http\Controllers\EloquentController;

class UsersController extends EloquentController
{

    /**
     * UsersController constructor.
     * @param Hydrator $hydrator
     */
    public function __construct(Hydrator $hydrator)
    {
        parent::__construct(new User(), $hydrator);
    }

    /**
     * @inheritdoc
     */
    protected function getRequestHandler()
    {
        return Request::class;
    }
}