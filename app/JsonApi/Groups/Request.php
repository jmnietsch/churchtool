<?php


namespace App\JsonApi\Groups;


use CloudCreativity\JsonApi\Http\Requests\RequestHandler;

class Request extends RequestHandler
{
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