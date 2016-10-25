<?php

namespace App\Api\V1\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

/**
 * This serves as a basis for all api controllers. Add API helper functions here.
 */
class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;

    /**
     * Converts the keys in the given array to snake case.
     *
     * @param array[] $array
     * @return array
     */
    protected function toSnakeCase($array)
    {
        $result = [];

        foreach ($array as $key => $value) {
            $result[snake_case($key)] = is_array($value) ? $this->toSnakeCase($value) : $value;
        }

        return $result;
    }

}