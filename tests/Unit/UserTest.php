<?php

namespace App\Tests\Unit;

use App\Models\User\Group;
use App\Models\User\User;
use App\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testIsAdminOf()
    {
        $user = \App\Models\User\User::whereId(3)->first();
        $groups = \App\Models\User\Group::all();

        $this->assertTrue($user->isAdminOf($groups[0]));
        $this->assertFalse($user->isAdminOf($groups[1]));
    }

    public function testIsMemberOf()
    {
        $user = \App\Models\User\User::whereId(4)->first();
        $groups = \App\Models\User\Group::all();

        $this->assertTrue($user->isMemberOf($groups[1]));
        $this->assertFalse($user->isMemberOf($groups[0]));
    }

    public function testIsAdminOrMemberOf()
    {
        $user1 = \App\Models\User\User::whereId(3)->first();
        $user2 = \App\Models\User\User::whereId(4)->first();
        $groups = \App\Models\User\Group::all();

        $this->assertTrue($user1->isAdminOrMemberOf($groups[0]));
        $this->assertTrue($user1->isAdminOrMemberOf($groups[0]));
        $this->assertFalse($user2->isAdminOrMemberOf($groups[0]));
    }

    public function testSetAdmin()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $group = Group::find(1);

        $user->setAdminOf($group);

        $this->seeInDatabase(
            'group_user',
            [
                'user_id' => $user->id,
                'group_id' => $group->id,
                'is_admin' => true,
            ]
        );
    }

    public function testSetMember()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $group = Group::find(1);

        $user->setMemberOf($group);

        $this->seeInDatabase(
            'group_user',
            [
                'user_id' => $user->id,
                'group_id' => $group->id,
                'is_admin' => false,
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