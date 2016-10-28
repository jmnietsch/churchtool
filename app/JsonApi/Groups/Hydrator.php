<?php


namespace App\JsonApi\Groups;


use CloudCreativity\LaravelJsonApi\Hydrator\EloquentHydrator;

class Hydrator extends EloquentHydrator
{

    protected $attributes = [
        'name',
        'member-capabilities',
        'admin-capabilities',
    ];

}