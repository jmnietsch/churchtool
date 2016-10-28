<?php


namespace App\JsonApi\Users;


use CloudCreativity\JsonApi\Http\Requests\RequestHandler;

class Request extends RequestHandler
{

    protected $hasMany = [
        'groups-admin',
        'groups-member'
    ];

    protected $allowedIncludePaths = [
        'groups-admin',
        'groups-member'
    ];

    /**
     * Request constructor.
     * @param Validators $validator
     */
    public function __construct(Validators $validator)
    {
        parent::__construct(null, $validator);
    }

    /**
     * @return string
     */
    public function getResourceType()
    {
        return Schema::RESOURCE_TYPE;
    }

}