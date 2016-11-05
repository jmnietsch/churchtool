<?php

namespace App\JsonApi\Users;


use App\JsonApi\Exceptions\UnauthorizedCreateException;
use App\JsonApi\Exceptions\UnauthorizedReadException;
use App\JsonApi\Exceptions\UnauthorizedRelationshipUpdateException;
use App\JsonApi\Exceptions\UnauthorizedUpdateException;
use App\Models\User\Group;
use App\Models\User\User;
use CloudCreativity\JsonApi\Contracts\Authorizer\AuthorizerInterface;
use CloudCreativity\JsonApi\Contracts\Object\RelationshipInterface;
use CloudCreativity\JsonApi\Contracts\Object\ResourceInterface;
use CloudCreativity\JsonApi\Exceptions\RuntimeException;
use Gate;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Exceptions\ErrorCollection;

class Authorizer implements AuthorizerInterface
{

    /**
     * @var ErrorCollection
     */
    protected $errors;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->errors = new ErrorCollection();
    }

    /**
     * @inheritdoc
     */
    public function canCreate(ResourceInterface $resource, EncodingParametersInterface $parameters)
    {
        if (Gate::allows('create-user')) {
            return true;
        } else {
            $this->errors->add(
                new UnauthorizedCreateException(
                    'Creation denied',
                    'Users must at least have capability MANAGE_USERS to create this resource',
                    '/data'
                )
            );

            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function canRead($record, EncodingParametersInterface $parameters)
    {
        return $this->canReadMany($parameters);
    }

    /**
     * @inheritdoc
     */
    public function canReadMany(EncodingParametersInterface $parameters)
    {
        $includes = $parameters->getIncludePaths();
        $check = true;

        if (!Gate::allows('get-user')) {
            $this->errors->add(
                new UnauthorizedUpdateException(
                    'Read access denied',
                    'Users must at least have capability VIEW_USER_NAMES to access this resource',
                    '/data'
                )
            );
            $check = false;
        }

        if (!Gate::allows('get-group') and (
                in_array('groups-member', $includes) or in_array('groups-admin', $includes)
            )
        ) {
            $this->errors->add(
                new UnauthorizedUpdateException(
                    'Read access denied',
                    'Users must have capability VIEW_GROUP to access the group relationships',
                    '/data/relationships'
                )
            );
            $check = false;
        }

        return $check;
    }

    /**
     * @inheritdoc
     */
    public function canUpdate(
        $record,
        ResourceInterface $resource,
        EncodingParametersInterface $parameters
    ) {
        if (!$record instanceof User) {
            throw new RuntimeException('User expected');
        }

        if (Gate::allows('update-user', $record)) {
            $check = true;

            if ($resource->hasRelationships()) {
                foreach ($resource->getRelationships()->getAll() as $key => $relationship) {
                    $check = $this->canModifyRelationship($key, $record, $relationship, $parameters)
                        ? $check : false;
                }
            }

            return $check;
        } else {
            $this->errors->add(
                new UnauthorizedUpdateException(
                    'Update denied',
                    'Users must at least have capability MANAGE_USERS to update an arbitrary user',
                    '/data/attributes'
                )
            );

            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function canModifyRelationship(
        $relationshipKey,
        $record,
        RelationshipInterface $relationship,
        EncodingParametersInterface $parameters
    ) {
        if (!$record instanceof User) {
            throw new RuntimeException('User expected');
        }

        switch ($relationshipKey) {
            case 'groups-member':
                return $this->canModifyGroupsMemberRelationship(
                    $record,
                    $relationship,
                    $parameters
                );
            case 'groups-admin':
                return $this->canModifyGroupsAdminRelationship($record, $relationship, $parameters);
        }

        return false;
    }

    public function canModifyGroupsMemberRelationship(
        User $record,
        RelationshipInterface $relationship,
        EncodingParametersInterface $parameters
    ) {
        $currentIds = $record->groupsMember()->getRelatedIds()->toArray();
        $thenIds = $relationship->isHasMany() ? $relationship->getIdentifiers()->getIds() : [];
        $deletedIds = array_diff($currentIds, $thenIds);
        $addedIds = array_diff($thenIds, $currentIds);
        $changedIds = array_merge($deletedIds, $addedIds);
        $changedGroups = Group::findMany($changedIds);

        $check = true;
        foreach ($changedGroups as $group) {
            if (!Gate::allows('group-manage-members', $group)) {
                /** @var Group $group */

                $this->errors->add(
                    new UnauthorizedRelationshipUpdateException(
                        'Updating relationships denied',
                        'User must have MANAGE_GROUPS capability or must be an administrator of '
                        .$group->name,
                        'groups-member'
                    )
                );
                $check = false;
            }
        }

        return $check;
    }

    public function canModifyGroupsAdminRelationship(
        User $record,
        RelationshipInterface $relationship,
        EncodingParametersInterface $parameters
    ) {
        if (Gate::allows('group-manage-admins')) {
            return true;
        } else {
            $this->errors->add(
                new UnauthorizedRelationshipUpdateException(
                    'Updating relationships denied',
                    'User must have MANAGE_GROUPS capability to update group admins',
                    'groups-admin'
                )
            );

            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function canDelete($record, EncodingParametersInterface $parameters)
    {
        if (!$record instanceof User) {
            throw new RuntimeException('User expected');
        }

        if (Gate::allows('delete-user')) {
            return true;
        } else {
            $this->errors->add(
                new UnauthorizedUpdateException(
                    'Delete request denied',
                    'Users must at least have capability MANAGE_USERS to delete a user',
                    '/data'
                )
            );

            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function canReadRelationship(
        $relationshipKey,
        $record,
        EncodingParametersInterface $parameters
    ) {
        if (!$record instanceof User) {
            throw new RuntimeException('User expected');
        }

        return $this->canReadRelatedResource($relationshipKey, $record, $parameters);
    }

    /**
     * @inheritdoc
     */
    public function canReadRelatedResource(
        $relationshipKey,
        $record,
        EncodingParametersInterface $parameters
    ) {
        if (!$record instanceof User) {
            throw new RuntimeException('User expected');
        }

        if (Gate::allows('get-group')) {
            return true;
        } else {
            $this->errors->add(
                new UnauthorizedReadException(
                    'Read access denied',
                    'Users must at least have capability VIEW_GROUPS to read the relationship',
                    '/data/relationships'
                )
            );

            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function getErrors()
    {
        return $this->errors;
    }

}