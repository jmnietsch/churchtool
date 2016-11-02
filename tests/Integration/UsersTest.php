<?php

namespace App\Tests\Integration;

use App\User;

class UsersTest extends TestCase
{

    /**
     * Assert that manager sees everything
     */
    public function testManagerReadModel()
    {
        $model = $this->viewNamesUser();
        $this->doRead(
            $model,
            ['include' => 'groups-member,groups-admin'],
            $this->authenticate($this->managerUser())
        );

        $data = [
            'type' => 'users',
            'id' => $model->id,
            'attributes' => [
                'created-at' => $model->created_at->toW3cString(),
                'updated-at' => $model->updated_at->toW3cString(),
                'email' => $model->email,
                'sex' => $model->sex,
                'first-name' => $model->first_name,
                'last-name' => $model->last_name,
                'date-of-birth' => $model->date_of_birth->toDateString(),
                'active' => (int)$model->active,
            ],
            'relationships' => [
                'groups-admin' => [
                    'links' => [

                    ],
                ],
                'groups-member' => [
                    'links' => [

                    ],
                ],
            ],
        ];

        $this->assertReadResponse($data);
    }

    /**
     * Return a persistent user model that can only VIEW_USER_NAMES
     */
    protected function viewNamesUser()
    {
        return User::find(4);
    }

    /**
     * Return a persistent user model that can MANAGE_USERS
     *
     * @return User
     */
    protected function managerUser()
    {
        return User::find(3);
    }

    /**
     * Assert that user that can only VIEW_USER_NAMES does not see email and date-of-birth
     */
    public function testViewNamesUserReadModel()
    {
        $model = $this->viewNamesUser();
        $this->doRead(
            $model,
            ['include' => 'groups-member,groups-admin'],
            $this->authenticate($this->viewNamesUser())
        );

        $r = $this->decodeResponseJson()['data']['attributes'];
        $this->assertArrayNotHasKey('email', $r);
        $this->assertArrayNotHasKey('date-of-birth', $r);
    }

    /**
     * Assert that a user that has capability MANAGE_USERS can create a new user
     */
    public function testCreateUser()
    {
        $data = [
            'type' => 'users',
            'attributes' => [
                'email' => 'foo@bar.com',
                'sex' => 'f',
                'first-name' => 'Foo',
                'last-name' => 'Bar',
                'date-of-birth' => '2000-08-11',
                'active' => 1,
            ],
            'relationships' => [
                'groups-member' => [
                    'data' => [
                        [
                            'type' => 'groups',
                            'id' => 1,
                        ],
                    ],
                ],
                'groups-admin' => [
                    'data' => [
                        [
                            'type' => 'groups',
                            'id' => 2,
                        ],
                    ],
                ],
            ],
        ];

        $this->doCreate($data, [], $this->authenticate($this->managerUser()));

        $this->seeInDatabase(
            'users',
            [
                'email' => 'foo@bar.com',
                'sex' => 'f',
                'first_name' => 'Foo',
                'last_name' => 'Bar',
                'active' => 1,
            ]
        );
        $this->seeInDatabase(
            'group_user',
            [
                'user_id' => $this->decodeResponseJson()['data']['id'],
                'group_id' => 1,
                'is_admin' => false,
            ]
        );
        $this->seeInDatabase(
            'group_user',
            [
                'user_id' => $this->decodeResponseJson()['data']['id'],
                'group_id' => 2,
                'is_admin' => true,
            ]
        );
    }

    /**
     * Assert that unauthorized creating user fails
     */
    public function testUnauthorizedCreateUser()
    {
        $data = [
            'type' => 'users',
            'attributes' => [
                'email' => 'foo@bar.com',
                'sex' => 'f',
                'first-name' => 'Foo',
                'last-name' => 'Bar',
                'date-of-birth' => '2000-08-11',
                'active' => 1,
            ],
        ];

        $this->doCreate($data, [], $this->authenticate($this->unprivilegedUser()));

        $this->seeStatusCode(403);

        $this->notSeeInDatabase(
            'users',
            [
                'email' => 'foo@bar.com',
            ]
        );
    }

    /**
     * Return a persistent user model that has no privileges
     */
    protected function unprivilegedUser()
    {
        return User::find(5);
    }

    /**
     * Assert that user with capability MANAGE_USERS can update a user and it's relationships
     */
    public function testUpdateUser()
    {
        $data = [
            'type' => 'users',
            'id' => 6,
            'attributes' => [
                'email' => 'foo@bar.com',
            ],
            'relationships' => [
                'groups-admin' => [
                    'data' => [
                    ],
                ],
                'groups-member' => [
                    'data' => [
                        ['type' => 'groups', 'id' => 2],
                    ],
                ],
            ],
        ];

        $this->doUpdate($data, [], $this->authenticate($this->managerUser()));
        $this->seeStatusCode(200);

        $this->seeInDatabase(
            'users',
            [
                'id' => 6,
                'email' => 'foo@bar.com',
            ]
        );
        $this->notSeeInDatabase(
            'group_user',
            [
                'user_id' => 6,
                'group_id' => 3,
            ]
        );
        $this->seeInDatabase(
            'group_user',
            [
                'user_id' => 6,
                'group_id' => 2,
                'is_admin' => false,
            ]
        );
    }

    /**
     * Assert that user without capability MANAGE_USERS can update its own user object
     * but not its group memberships
     */
    public function testUpdateOwnUserObject()
    {
        // make sure that member of no groups before
        $this->unprivilegedUser()->groups()->sync([]);

        $data = [
            'type' => 'users',
            'id' => $this->unprivilegedUser()->id,
            'attributes' => [
                'email' => 'foo@bar.com',
            ],
        ];

        $this->doUpdate($data, [], $this->authenticate($this->unprivilegedUser()));

        $this->seeStatusCode(200);
        $this->seeInDatabase(
            'users',
            [
                'id' => $this->unprivilegedUser()->id,
                'email' => 'foo@bar.com',
            ]
        );

        $data = [
            'type' => 'users',
            'id' => $this->unprivilegedUser()->id,
            'relationships' => [
                'groups-admin' => [
                    'data' => [
                        [
                            'type' => 'groups',
                            'id' => 2,
                        ],
                    ],
                ],
            ],
        ];

        $this->doUpdate($data, [], $this->authenticate($this->unprivilegedUser()));

        $this->seeStatusCode(403);
        $this->notSeeInDatabase(
            'group_user',
            [
                'user_id' => $this->unprivilegedUser()->id,
                'group_id' => 2,
            ]
        );
    }

    /**
     * Assert that an admin of some group can add members to this group
     */
    public function testGroupAdminCanAddMembers()
    {
        // make sure that member of no groups before
        $this->unprivilegedUser()->groups()->sync([]);

        $data = [
            'type' => 'users',
            'id' => $this->unprivilegedUser()->id,
            'relationships' => [
                'groups-member' => [
                    'data' => [
                        [
                            'type' => 'groups',
                            'id' => 1,
                        ],
                    ],
                ],
            ],
        ];

        $this->doUpdate($data, [], $this->authenticate($this->managerUser()));
        $this->seeStatusCode(200);
        $this->seeInDatabase(
            'group_user',
            [
                'user_id' => $this->unprivilegedUser()->id,
                'group_id' => 1,
                'is_admin' => false,
            ]
        );
    }

    /**
     * Assert that user without MANAGE_USERS capability cannot modify an arbitrary user
     */
    public function testUnauthorizedUpdateUser()
    {
        $data = [
            'type' => 'users',
            'id' => 6,
            'attributes' => [
                'email' => 'foo@bar.com',
            ],
        ];

        $this->doUpdate($data, [], $this->authenticate($this->unprivilegedUser()));

        $this->seeStatusCode(403);

        $this->notSeeInDatabase(
            'users',
            [
                'email' => 'foo@bar.com',
            ]
        );
    }

    /**
     * Test that user with MANAGE_USERS capability can delete an arbitrary user
     */
    public function testDeleteUser()
    {
        $model = User::find(1);
        $this->doDelete(1, [], $this->authenticate($this->managerUser()));
        $this->assertDeleteResponse();
        $this->assertModelDeleted($model);
    }

    /**
     * Test that user without MANAGE_USERS capability cannot delete an arbitrary user
     */
    public function testUnauthorizedDeleteUser()
    {
        $email = User::find(1)->email;
        $this->doDelete(1, [], $this->authenticate($this->unprivilegedUser()));

        $this->seeStatusCode(403);

        $this->seeInDatabase(
            'users',
            [
                'email' => $email,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    protected function getResourceType()
    {
        return 'users';
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
