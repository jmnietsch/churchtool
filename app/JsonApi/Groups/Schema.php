<?php


namespace App\JsonApi\Groups;


use App\Capability;
use CloudCreativity\LaravelJsonApi\Schema\EloquentSchema;
use Illuminate\Database\Eloquent\Model;

class Schema extends EloquentSchema
{

    /**
     * The json-api resource type of the User model.
     */
    const RESOURCE_TYPE = 'groups';

    /**
     * @inheritdoc
     */
    protected $attributes = [
        'name',
        'member_capabilities',
        'admin_capabilities',
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
        if ($modelKey == 'member_capabilities' or $modelKey == 'admin_capabilities') {
            $value = Capability::toArray($value);
        }

        return parent::serializeAttribute(
            $value,
            $model,
            $modelKey
        );
    }


}