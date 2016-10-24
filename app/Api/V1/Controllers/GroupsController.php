<?php

namespace App\Api\V1\Controllers;


use App\Capability;
use App\Group;
use App\Http\Requests\CreateUpdateGroupRequest;
use App\Http\Requests\DeleteGroupRequest;
use App\Http\Requests\GetGroupsRequest;
use App\Transformers\V1\GroupsTransformer;

class GroupsController extends BaseController
{

    public function index(GetGroupsRequest $request)
    {
        $groups = Group::all();

        return $this->response->collection($groups, new GroupsTransformer(), ['key' => 'groups']);
    }

    public function create(CreateUpdateGroupRequest $request)
    {
        $group = Group::create(
            [
                'name' => $request->get('name'),
                'member_capabilities' => Capability::fromArray($request->get('memberCapabilities')),
                'admin_capabilities' => Capability::fromArray($request->get('adminCapabilities')),
            ]
        );
        $group->save();

        return $this->response->created('api/groups/'.$group->id);
    }

    public function update(Group $group, CreateUpdateGroupRequest $request)
    {
        $group->fill(
            [
                'name' => $request->get('name'),
                'member_capabilities' => Capability::fromArray($request->get('memberCapabilities')),
                'admin_capabilities' => Capability::fromArray($request->get('adminCapabilities')),
            ]
        )->save();

        return $this->response->noContent();
    }

    public function delete(Group $group, DeleteGroupRequest $request)
    {
        $group->delete();

        return $this->response->noContent();
    }

}