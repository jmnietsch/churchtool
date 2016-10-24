<?php


namespace App\Transformers\V1;


use App\Capability;
use App\Group;
use League\Fractal\TransformerAbstract;

class GroupsTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['admins', 'members'];

    public function transform(Group $group)
    {
        return [
            'id' => (int)$group->id,
            'name' => $group->name,
            'memberCapabilities' => Capability::toArray($group->member_capabilities),
            'adminCapabilities' => Capability::toArray($group->admin_capabilities),
        ];
    }

    public function includeAdmins(Group $group)
    {
        return $this->collection(
            $group->users()->wherePivot('is_admin', true)->get(),
            new UsersTransformer(),
            'users'
        );
    }

    public function includeMembers(Group $group)
    {
        return $this->collection(
            $group->users()->wherePivot('is_admin', false)->get(),
            new UsersTransformer(),
            'users'
        );
    }

}