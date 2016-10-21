<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Authenticate given user and return authentication headers. Taken from:
     * https://dotdev.co/test-driven-api-development-using-laravel-dingo-and-jwt-with-documentation-ae4014260148#.an5isxoeb
     *
     * @param $user \App\User
     * @return array
     */
    public function authenticate(\App\User $user)
    {
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);
        $headers['Authorization'] = 'Bearer '.$token;

        return $headers;
    }
}
