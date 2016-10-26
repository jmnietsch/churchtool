<?php


namespace App\JsonApi\Users;


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

        return [
            'email' => "$required|email|unique:users,email|between:1,100",
            'sex' => "$required|in:m,f",
            'first-name' => "$required|between:1,100",
            'last-name' => "$required|between:1,100",
            'date-of-birth' => "$required|date|before:today",
            'active' => "$required|boolean"
        ];
    }

    /**
     * @inheritdoc
     */
    protected function relationshipRules(
        RelationshipsValidatorInterface $relationships,
        $resourceType,
        $record = null
    )
    {
    }
}