<?php

namespace App\Tests\Integration;

use App\Tests\TestCase as Base;
use CloudCreativity\LaravelJsonApi\Testing\InteractsWithModels;
use CloudCreativity\LaravelJsonApi\Testing\InteractsWithResources;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use JWTAuth;

abstract class TestCase extends Base
{
    use DatabaseMigrations, InteractsWithModels, InteractsWithResources;

    /**
     * Authenticate given user and return authentication headers. Taken from:
     * https://dotdev.co/test-driven-api-development-using-laravel-dingo-and-jwt-with-documentation-ae4014260148#.an5isxoeb
     *
     * @param $user \App\Models\User\User
     * @return array
     */
    public function authenticate(\App\Models\User\User $user)
    {
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);
        $headers['Authorization'] = 'Bearer '.$token;

        return $headers;
    }

    /**
     * @inheritdoc
     */
    protected function getRoutePrefix()
    {
        return 'api-v1::';
    }

}
