<?php

namespace App\Api\V1\Controllers;

use App\Http\Requests\GetUsersRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Transformers\V1\UsersTransformer;
use App\User;
use Dingo\Api\Routing\Helpers;


class UsersController extends BaseController
{
    use Helpers;

    public function index(GetUsersRequest $request)
    {
        $users = User::all();

        return $this->response->collection($users, new UsersTransformer, ['key' => 'users']);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $user->fill($this->toSnakeCase($request->only('sex', 'firstName', 'lastName')))->save();
    }

}