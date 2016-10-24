<?php

namespace App\Exceptions;


use Exception;

class InvalidCapabilityException extends Exception
{

    public function __construct()
    {
        parent::__construct("The given capability is not valid.");
    }


}