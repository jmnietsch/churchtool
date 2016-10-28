<?php


namespace App\JsonApi\Users;


use CloudCreativity\LaravelJsonApi\Schema\EloquentSchema;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * @inheritdoc
     */
    protected function serializeAttribute($value, Model $model, $modelKey)
    {
        if ($modelKey == 'date_of_birth') {
            /** @var \DateTime $value */
            return $value->format('Y-m-d');
        }

        return parent::serializeAttribute(
            $value,
            $model,
            $modelKey
        );
    }


}