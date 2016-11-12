<?php

use App\Models\Room\Room;
use App\Models\User\Capability as C;
use App\Models\User\Group;
use App\Models\User\User;
use Faker\Generator;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(
    User::class,
    function (Generator $faker) {
    $sex = $faker->boolean() ? 'm' : 'f';
    return [
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'sex' => $sex,
        'first_name' => $sex == 'm' ? $faker->firstNameMale : $faker->firstNameFemale,
        'last_name' => $faker->lastName,
        'active' => $faker->boolean(80)
    ];
});

$factory->define(
    Group::class,
    function () {
    static $i = 0;

    $keys = [
        'name',
        'member_capabilities',
        'admin_capabilities',
        'enable_mail'
    ];
    $values = [
        ['Mitglieder', C::VIEW_USER_ADDRESS_DATA, 0b0, false],
        ['Gemeindeleitung', C::MANAGE_USERS, 0b0, false]
    ];

    return array_combine($keys, $values[$i++]);
});

$factory->define(
    Room::class,
    function (Generator $faker) {
        return [
            'name' => $faker->city,
        ];
    }
);
