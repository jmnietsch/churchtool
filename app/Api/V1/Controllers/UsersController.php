<?php

namespace App\Api\V1\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\DeleteUserRequest;
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
        $user->fill(
            $this->toSnakeCase(
                $request->only('sex', 'firstName', 'lastName', 'email', 'dateOfBirth')
            )
        )->save();

        return $this->response->noContent();
    }

    public function create(CreateUserRequest $request)
    {
        $user = new User();
        $user->fill(
            $this->toSnakeCase(
                $request->only('sex', 'firstName', 'lastName', 'email', 'dateOfBirth')
            )
        );

        // set some random password (user needs to set the concrete password on it's own)
        $user->setPasswordAttribute(str_random(20));
        $user->save();

        return $this->response->created('api/users/'.$user->id);
    }

    public function delete(User $user, DeleteUserRequest $request)
    {
        $user->delete();

        return $this->response->noContent();
    }

}