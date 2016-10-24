<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function (Dingo\Api\Routing\Router $api) {

    $api->post('auth/login', 'App\Api\V1\Controllers\AuthController@login');
    $api->post('auth/recovery', 'App\Api\V1\Controllers\AuthController@recovery');
    $api->post('auth/reset', 'App\Api\V1\Controllers\AuthController@reset');

    $api->group(
        ['middleware' => 'api.auth'],
        function (Dingo\Api\Routing\Router $api) {

            $api->get('users', 'App\Api\V1\Controllers\UsersController@index');
            $api->post('users', 'App\Api\V1\Controllers\UsersController@create');
            $api->put('users/{user}', 'App\Api\V1\Controllers\UsersController@update');
            $api->delete('users/{user}', 'App\Api\V1\Controllers\UsersController@delete');

            $api->get('groups', 'App\Api\V1\Controllers\GroupsController@index');
            $api->post('groups', 'App\Api\V1\Controllers\GroupsController@create');
            $api->put('groups/{group}', 'App\Api\V1\Controllers\GroupsController@update');
            $api->delete('groups/{group}', 'App\Api\V1\Controllers\GroupsController@delete');
        }
    );

});
