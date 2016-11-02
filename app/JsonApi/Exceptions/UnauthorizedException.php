<?php

namespace App\JsonApi\Exceptions;


use Neomerx\JsonApi\Contracts\Document\ErrorInterface;

abstract class UnauthorizedException implements ErrorInterface
{

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $detail;

    /**
     * @var string
     */
    protected $source;

    /**
     * Constructs this Exception.
     *
     * @param string $title
     * @param string|null $detail
     * @param string|null $source
     */
    public function __construct($title, $detail = null, $source = null)
    {
        $this->title = $title;
        $this->detail = $detail;
        $this->source = $source;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return __NAMESPACE__.'\\'.__CLASS__;
    }

    /**
     * @inheritDoc
     */
    public function getLinks()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return "403";
    }

    /**
     * @inheritDoc
     */
    public function getCode()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @inheritDoc
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @inheritDoc
     */
    public function getSource()
    {
        return [$this->source];
    }

    /**
     * @inheritDoc
     */
    public function getMeta()
    {
        return null;
    }


}