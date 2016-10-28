<?php


namespace App\JsonApi\Users;


use CloudCreativity\JsonApi\Contracts\Object\RelationshipInterface;
use CloudCreativity\LaravelJsonApi\Hydrator\EloquentHydrator;

class Hydrator extends EloquentHydrator
{

    protected $attributes = [
        'email',
        'sex',
        'first-name',
        'last-name',
        'date-of-birth',
        'active',
        'password'
    ];

    protected $relationships = [
        'groups-member',
        'groups-admin'
    ];

    protected function hydrateGroupsAdminRelationship(RelationshipInterface $relationship,
                                                      $record)
    {

        $ids = $relationship->getIdentifiers()->getIds();

        $update = array_combine($ids, array_fill(0, count($ids), [
            'is_admin' => true
        ]));

        $record->groups()->sync($update);
    }

}