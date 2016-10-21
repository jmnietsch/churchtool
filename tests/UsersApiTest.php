<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetUserList()
    {
        // Authenticate user with sufficient rights and make request
        $this->get('api/users', $this->authenticate(\App\User::find(3)));

        // ensure structural correctness
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
                    'firstName' => 'Mathis',
                    'lastName' => 'Hoffmann',
                    'sex' => 'm',
                ],
            ]
        );
    }

    public function testUnauthorizedGetUserList()
    {
        // Authenticate user with insufficient rights and make request
        $this->get('api/users', $this->authenticate(\App\User::find(5)))->seeStatusCode(403);
    }

    public function testUpdateUser()
    {
        // Authenticate user with sufficient rights and make request
        $this->put(
            'api/users/1',
            [
                'sex' => 'f',
                'firstName' => 'Max',
                'lastName' => 'Mustermann',
            ],
            $this->authenticate(\App\User::find(3))
        );

        // check that the item has been updated
        $this->seeInDatabase(
            'users',
            [
                'id' => 1,
                'sex' => 'f',
                'first_name' => 'Max',
                'last_name' => 'Mustermann',
            ]
        );
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
