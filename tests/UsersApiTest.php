<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersApiTest extends TestCase
{
    use DatabaseMigrations;

    protected static $db_initialized = false;

    /**
     * Test that a user with the required capabilities can see all user data here.
     */
    public function testGetUserList()
    {
        $this->get('api/users', $this->authenticate(\App\User::find(3)));

        // ensure structural correctness (we omit dateOfBirth here, because its optional)
        $this->seeJsonStructure(
            [
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'sex',
                            'firstName',
                            'lastName',
                            'email',
                        ],
                    ],
                ],
            ]
        );
        $this->seeStatusCode(200);

        // test detailed correctness by example
        $this->seeJsonContains(
            [
                'type' => 'users',
                'id' => '3',
                'attributes' => [
                    'sex' => 'm',
                    'firstName' => 'Mathis',
                    'lastName' => 'Hoffmann',
                    'email' => 'mathis@hoffpost.de',
                    'dateOfBirth' => \Carbon\Carbon::create(1980, 3, 11)->toDateString(),
                ],
            ]
        );
    }

    /**
     * Test that a user that is only capable of seing user names does not see more.
     */
    public function testGetMinimumUserList()
    {
        $this->get('api/users', $this->authenticate(\App\User::find(4)));

        $this->seeJsonStructure(
            [
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'sex',
                            'firstName',
                            'lastName',
                        ],
                    ],
                ],
            ]
        );
        $this->seeStatusCode(200);

        // test detailed correctness by example
        $this->seeJsonContains(
            [
                'type' => 'users',
                'id' => '3',
                'attributes' => [
                    'sex' => 'm',
                    'firstName' => 'Mathis',
                    'lastName' => 'Hoffmann',
                ],
            ]
        );
    }

    /**
     * Test that a user without permission gets an error here.
     */
    public function testUnauthorizedGetUserList()
    {
        $this->get('api/users', $this->authenticate(\App\User::find(5)))->seeStatusCode(403);
    }

    /**
     * Test that updating a user with the required permissions.
     */
    public function testUpdateUser()
    {
        $this->put(
            'api/users/1',
            [
                'sex' => 'f',
                'firstName' => 'Max',
                'lastName' => 'Mustermann',
                'email' => 'max@mustermann.com',
                'dateOfBirth' => \Carbon\Carbon::create(1986, 12, 1)->toDateString(),
            ],
            $this->authenticate(\App\User::find(3))
        );

        $this->seeStatusCode(204);

        $this->seeInDatabase(
            'users',
            [
                'id' => 1,
                'sex' => 'f',
                'first_name' => 'Max',
                'last_name' => 'Mustermann',
                'email' => 'max@mustermann.com',
            ]
        );
    }

    /**
     * Test that updating a user without the required permissions fails.
     */
    public function testUnauthorizedUpdateUser()
    {
        $this->put(
            'api/users/1',
            [
                'sex' => 'f',
                'firstName' => 'Max',
                'lastName' => 'Mustermann',
                'email' => 'max@mustermann.com',
                'dateOfBirth' => \Carbon\Carbon::create(1986, 12, 1)->toDateString(),
            ],
            $this->authenticate(\App\User::find(5))
        );

        $this->seeStatusCode(403);

        $this->notSeeInDatabase(
            'users',
            [
                'id' => 1,
                'sex' => 'f',
                'first_name' => 'Max',
                'last_name' => 'Mustermann',
                'email' => 'max@mustermann.com',
            ]
        );
    }

    /**
     * Test creating a user with the required permissions.
     */
    public function testCreateUser()
    {
        $this->post(
            'api/users',
            [
                'sex' => 'm',
                'firstName' => 'Martin',
                'lastName' => 'Winter',
                'email' => 'martin@winter.com',
                'dateOfBirth' => \Carbon\Carbon::create(1977, 8, 12)->toDateString(),
            ],
            $this->authenticate(\App\User::find(3))
        );

        $this->seeStatusCode(201);

        // TODO: Test for location header (does not work with seeHeader because response->location is an array)

        // unfortunately we cannot use seeInDatabase here, because it cannot deal with plain dates
        $n = DB::query()->select()->from('users')
            ->where('sex', 'm')
            ->where('first_name', 'Martin')
            ->where('last_name', 'Winter')
            ->where('email', 'martin@winter.com')
            ->whereDate('date_of_birth', '=', \Carbon\Carbon::create(1977, 8, 12)->toDateString())
            ->count();
        $this->assertEquals(1, $n, 'Record not found in database.');
    }

    /**
     * Test creating a user without the required permissions fails.
     */
    public function testUnauthorizedCreateUser()
    {
        $this->post(
            'api/users',
            [
                'sex' => 'm',
                'firstName' => 'Martin',
                'lastName' => 'Winter',
                'email' => 'martin@winter.com',
                'dateOfBirth' => \Carbon\Carbon::create(1977, 8, 12)->toDateString(),
            ],
            $this->authenticate(\App\User::find(5))
        );

        $this->seeStatusCode(403);

        $this->notSeeInDatabase('users', ['email' => 'martin@winter.com']);
    }

    /**
     * Test deleting a user with the required permissions.
     */
    public function testDeleteUser()
    {
        $this->delete('api/users/2', [], $this->authenticate(\App\User::find(3)));

        $this->seeStatusCode(204);

        $this->notSeeInDatabase('users', ['id' => 2]);
    }

    /**
     * Test that attempting to delete a user without the required permissions fails.
     */
    public function testUnauthorizedDeleteUser()
    {
        $this->delete('api/users/2', [], $this->authenticate(\App\User::find(5)));

        $this->seeStatusCode(403);

        $this->seeInDatabase('users', ['id' => 2]);
    }

    /**
     * Set up some users and groups.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->seed('UsersTableSeeder');
        $this->seed('GroupsTableSeeder');
        $this->seed('GroupUserTableSeeder');
    }

}
