<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testIsAdminOf()
    {
        $user = \App\User::whereId(3)->first();
        $groups = \App\Group::all();

        $this->assertTrue($user->isAdminOf($groups[0]));
        $this->assertFalse($user->isAdminOf($groups[1]));
    }

    public function testIsMemberOf()
    {
        $user = \App\User::whereId(4)->first();
        $groups = \App\Group::all();

        $this->assertTrue($user->isMemberOf($groups[1]));
        $this->assertFalse($user->isMemberOf($groups[0]));
    }

    public function testIsAdminOrMemberOf()
    {
        $user1 = \App\User::whereId(3)->first();
        $user2 = \App\User::whereId(4)->first();
        $groups = \App\Group::all();

        $this->assertTrue($user1->isAdminOrMemberOf($groups[0]));
        $this->assertTrue($user1->isAdminOrMemberOf($groups[0]));
        $this->assertFalse($user2->isAdminOrMemberOf($groups[0]));
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