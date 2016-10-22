<?php


namespace App\Transformers\V1;


use App\User;
use Gate;
use League\Fractal\TransformerAbstract;

class UsersTransformer extends TransformerAbstract
{
    /*protected $defaultIncludes = [
        'groups',
    ];*/

    public function transform(User $user)
    {
        $result = [
            'id' => (int)$user->id,
            'sex' => $user->sex,
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
        ];

        // check if we can include the users email
        if (Gate::allows('get-user-email', $user)) {
            $result = array_add($result, 'email', $user->email);
        }

        // check if we can include the date of birth
        if (Gate::allows('get-user-dateofbirth', $user)) {
            $result = array_add($result, 'dateOfBirth', $user->date_of_birth->toDateString());
        }

        return $result;
    }

    /*public function includeGroups(User $user)
    {
        return $this->collection($user->groups()->get(), new GroupsTransformer(), 'groups');
    }*/

}