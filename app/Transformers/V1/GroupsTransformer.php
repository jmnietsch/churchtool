<?php


namespace App\Transformers\V1;


use App\Group;
use League\Fractal\TransformerAbstract;

class GroupsTransformer extends TransformerAbstract
{

    public function transform(Group $group)
    {
        return [
            'id' => (int)$group->id,
            'name' => $group->name,
        ];
    }

}