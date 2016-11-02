<?php

namespace App\JsonApi\Exceptions;


class UnauthorizedRelationshipUpdateException extends UnauthorizedException
{

    /**
     * @var string
     */
    protected $relationshipKey;

    /**
     * UnauthorizedRelationshipUpdateException constructor.
     * @param string $title
     * @param string|null $detail
     * @param string $relationshipKey
     */
    public function __construct($title, $detail, $relationshipKey)
    {
        $this->relationshipKey = $relationshipKey;
        parent::__construct($title, $detail);
    }

    /**
     * @inheritDoc
     */
    public function getSource()
    {
        return ['data/relationships/'.$this->relationshipKey];
    }


}