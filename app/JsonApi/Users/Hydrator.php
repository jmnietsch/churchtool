<?php


namespace App\JsonApi\Users;


use App\Models\User\User;
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

    /**
     * Hydrate the group-admin relationships
     *
     * @param RelationshipInterface $relationship
     * @param User $record
     */
    protected function hydrateGroupsAdminRelationship(RelationshipInterface $relationship,
                                                      $record)
    {
        $this->syncGroups($relationship, $record, true);
    }

    /**
     * @param RelationshipInterface $relationship
     * @param User $record
     * @param boolean $is_admin
     */
    protected function syncGroups(
        RelationshipInterface $relationship,
        $record,
        $is_admin
    ) {
        if (is_null($record->id)) {
            $record->save();
        }

        if (!$relationship->isHasMany() or $relationship->count() == 0) {
            // delete all relationships where $is_admin matches
            $record->groups()->getBaseQuery()->where('is_admin', '=', $is_admin)->delete();

            return;
        }

        // construct update array for $is_admin-relationships
        $ids_this = $relationship->getIdentifiers()->getIds();
        $update_this = array_combine(
            $ids_this,
            array_fill(
                0,
                count($ids_this),
                [
                    'is_admin' => $is_admin,
        ]));

        // construct array to keep values of !$is_admin-relationships
        $ids_other = $record->groups()
            ->getBaseQuery()
            ->where('is_admin', '<>', $is_admin)
            ->pluck('group_id')->toArray();

        $keep_other = array_combine(
            $ids_other,
            array_fill(
                0,
                count($ids_other),
                [
                    'is_admin' => !$is_admin,
                ]
            )
        );

        $record->groups()->sync($update_this + $keep_other);
    }

    /**
     * Hydrate the group-member relationships
     *
     * @param RelationshipInterface $relationship
     * @param User $record
     */
    protected function hydrateGroupsMemberRelationship(
        RelationshipInterface $relationship,
        $record
    ) {
        $this->syncGroups($relationship, $record, false);
    }

}