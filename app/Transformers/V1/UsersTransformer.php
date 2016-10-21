<?php


namespace App\Transformers\V1;


use App\User;
use League\Fractal\TransformerAbstract;

class UsersTransformer extends TransformerAbstract
{
    /*protected $defaultIncludes = [
        'groups',
    ];*/

    public function transform(User $user)
    {
        return [
            'id' => (int)$user->id,
            'sex' => $user->sex,
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
        ];
    }

    /*public function includeGroups(User $user)
    {
        return $this->collection($user->groups()->get(), new GroupsTransformer(), 'groups');
    }*/

}