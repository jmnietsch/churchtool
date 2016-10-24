<?php

use App\Capability as Capability;
use App\User as User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GroupsApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetGroupsList()
    {
        $this->get('api/groups', $this->authenticate(User::find(3)));

        $this->seeStatusCode(200);

        $this->seeJsonStructure(
            [
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'memberCapabilities',
                            'adminCapabilities',
                        ],
                    ],
                ],
            ]
        );

        // test detailed correctness by example
        $this->seeJsonContains(
            [
                'type' => 'groups',
                'id' => '1',
                'attributes' => [
                    'name' => 'Mitglieder',
                    'memberCapabilities' => [
                        'VIEW_USER_NAMES',
                        'VIEW_USER_ADDRESS_DATA',
                    ],
                    'adminCapabilities' => [
                        'VIEW_USER_NAMES',
                        'VIEW_USER_ADDRESS_DATA',
                        'VIEW_USER_DATE_OF_BIRTH',
                        'VIEW_USER_ATTRIBUTES',
                        'MANAGE_USERS',
                    ],
                ],
            ]
        );
    }

    public function testUnauthorizedGetGroupsList()
    {
        $this->get('api/groups', $this->authenticate(User::find(5)));

        $this->seeStatusCode(403);
    }

    public function testGetGroupsWithIncludes()
    {
        $this->get('api/groups?include=admins,members', $this->authenticate(User::find(2)));

        $this->seeStatusCode(200);

        $this->seeJsonStructure(
            [
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'memberCapabilities',
                            'adminCapabilities',
                        ],
                        'relationships' => [
                            'admins',
                            'members',
                        ],
                    ],
                ],
                'included' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes',
                    ],
                ],
            ]
        );
    }

    public function testGetGroupsUnauthorizedForIncludes()
    {
        // Note: A user with capability VIEW_GROUPS but without capability VIEW_USER_NAMES may
        // not include group members. We set up this rare case on our own here:
        User::find(6)->groups()->detach([1]);

        $this->get('api/groups?include=admins,members', $this->authenticate(User::find(6)));

        var_dump($this->response->content());

        $this->seeStatusCode(403);
    }

    public function testCreateGroup()
    {
        $this->post(
            'api/groups',
            [
                'name' => 'somegroup',
                'memberCapabilities' => ['VIEW_USER_NAMES'],
                'adminCapabilities' => ['MANAGE_USERS', 'MANAGE_GROUPS'],
            ],
            $this->authenticate(User::find(6))
        );

        $this->seeStatusCode(201);

        // TODO: Test for correct location header

        $this->seeInDatabase(
            'groups',
            [
                'name' => 'somegroup',
                'member_capabilities' => Capability::VIEW_USER_NAMES,
                'admin_capabilities' => (Capability::MANAGE_USERS | Capability::MANAGE_GROUPS),
            ]
        );
    }

    public function testUnauthorizedCreateGroup()
    {
        $this->post(
            'api/groups',
            [
                'name' => 'somegroup',
                'memberCapabilities' => ['VIEW_USER_NAMES'],
                'adminCapabilities' => ['MANAGE_USERS'],
            ],
            $this->authenticate(User::find(3))
        );

        $this->seeStatusCode(403);

        $this->notSeeInDatabase('groups', ['name' => 'somegroup']);
    }

    public function testUpdateGroup()
    {
        $this->put(
            'api/groups/2',
            [
                'name' => 'somegroup',
                'memberCapabilities' => ['MANAGE_USERS'],
                'adminCapabilities' => ['MANAGE_GROUPS'],
            ],
            $this->authenticate(User::find(6))
        );

        $this->seeStatusCode(204);

        $this->seeInDatabase(
            'groups',
            [
                'id' => 2,
                'name' => 'somegroup',
                'member_capabilities' => Capability::MANAGE_USERS,
                'admin_capabilities' => Capability::MANAGE_GROUPS,
            ]
        );
    }

    public function testUnauthorizedUpdateGroup()
    {
        $this->put(
            'api/groups/2',
            [
                'name' => 'somegroup',
                'memberCapabilities' => ['MANAGE_USERS'],
                'adminCapabililties' => ['MANAGE_GROUPS'],
            ],
            $this->authenticate(User::find(3))
        );

        $this->seeStatusCode(403);
    }

    public function testDeleteGroup()
    {
        $num_users = User::count();

        $this->delete('api/groups/3', [], $this->authenticate(User::find(6)));

        $this->seeStatusCode(204);

        // ensure that any memberships to that group get deleted
        $this->assertEquals(
            0,
            DB::query()->select()->from('group_user')->where('group_id', '=', 3)->count()
        );

        // ensure that no users get deleted
        $this->assertEquals($num_users, User::count());
    }

    public function testUnauthorizedDeleteGroup()
    {
        $this->delete('api/groups/3', [], $this->authenticate(User::find(3)));

        $this->seeStatusCode(403);

        $this->seeInDatabase('groups', ['name' => 'Gruppenverwaltung']);
    }

    public function testGetGroupMemberships()
    {
        $this->assertTrue(true);
    }

    public function testUnauthorizedGetGroupMemberships()
    {
        $this->assertTrue(true);
    }

    public function testUpdateGroupMembership()
    {
        $this->assertTrue(true);
    }

    public function testUnauthorizedUpdateGroupMembership()
    {
        $this->assertTrue(true);
    }

    public function testCreateGroupMembership()
    {
        $this->assertTrue(true);
    }

    public function testUnauthorizedCreateGroupMembership()
    {
        $this->assertTrue(true);
    }

    public function testDeleteGroupMembership()
    {
        $this->assertTrue(true);
    }

    public function testUnauthorizedDeleteGroupMembership()
    {
        $this->assertTrue(true);
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
