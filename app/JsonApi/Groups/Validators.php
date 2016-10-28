<?php


namespace App\JsonApi\Groups;


use App\User;
use CloudCreativity\JsonApi\Contracts\Validators\RelationshipsValidatorInterface;
use CloudCreativity\LaravelJsonApi\Validators\AbstractValidatorProvider;

class Validators extends AbstractValidatorProvider
{

    /**
     * @inheritdoc
     */
    protected function attributeRules($resourceType, $record = null)
    {
        /** @var User $record */

        // The JSON API spec says the client does not have to send all attributes for an update request, so
        // if the record already exists we need to include a 'sometimes' before required.
        $required = $record ? 'sometimes|required' : 'required';

        // TODO Write capability validator and include here
        return [
            'name' => "$required|unique:groups,name|between:1,100",
            'member-capabilities' => "$required",
            'admin-capabilities' => "$required",
        ];
    }

    /**
     * @inheritdoc
     */
    protected function relationshipRules(
        RelationshipsValidatorInterface $relationships,
        $resourceType,
        $record = null
    ) {
    }
}