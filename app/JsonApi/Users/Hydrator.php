<?php


namespace App\JsonApi\Users;


use CloudCreativity\LaravelJsonApi\Hydrator\EloquentHydrator;

class Hydrator extends EloquentHydrator
{

    protected $attributes = [
        'email',
        'sex',
        'first-name',
        'last-name',
        'date-of-birth',
        'active',
        'password'
    ];

}