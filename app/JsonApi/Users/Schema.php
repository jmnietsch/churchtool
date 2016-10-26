<?php


namespace App\JsonApi\Users;


use CloudCreativity\LaravelJsonApi\Schema\EloquentSchema;

class Schema extends EloquentSchema
{

    /**
     * The json-api resource type of the User model.
     */
    const RESOURCE_TYPE = 'users';

    /**
     * @inheritdoc
     */
    protected $attributes = [
        'email',
        'sex',
        'first_name',
        'last_name',
        'date_of_birth',
        'active'
    ];

    /**
     * @inheritdoc
     */
    public function getResourceType()
    {
        return self::RESOURCE_TYPE;
    }


}