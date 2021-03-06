<?php

namespace App\Tests\Unit;

use App\Models\User\Capability as C;
use App\Models\User\User;
use App\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CapabilityTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var \App\Models\User\User
     */
    private $member;

    /**
     * @var \App\Models\User\User
     */
    private $admin;

    /**
     * @var \App\Models\User\Group
     */
    private $group1;

    /**
     * @var \App\Models\User\Group
     */
    private $group2;

    public function testMemberCapabilities()
    {
        $this->group1->allowMembersTo(C::VIEW_USER_NAMES);
        $this->seeInDatabase('groups', [
            'name' => $this->group1->name,
            'member_capabilities' => C::VIEW_USER_NAMES
        ]);
        $this->assertTrue($this->member->hasCapability(C::VIEW_USER_NAMES));
        $this->assertFalse($this->member->hasCapability(C::MANAGE_USERS));
    }

    public function testJointMemberCapabilities()
    {
        $this->group1->allowMembersTo(C::VIEW_USER_NAMES);
        $this->group2->allowMembersTo(C::VIEW_USER_ADDRESS_DATA);
        $this->assertTrue($this->member->hasCapability(C::VIEW_USER_NAMES));
        $this->assertTrue($this->member->hasCapability(C::VIEW_USER_ADDRESS_DATA));
    }

    public function testAdminCapabilities()
    {
        $this->group1->allowAdminsTo(C::VIEW_USER_NAMES);
        $this->seeInDatabase('groups', [
            'name' => $this->group1->name,
            'admin_capabilities' => C::VIEW_USER_NAMES
        ]);
        $this->assertTrue($this->admin->hasCapability(C::VIEW_USER_NAMES));
        $this->assertFalse($this->admin->hasCapability(C::MANAGE_USERS));
    }

    public function testJointAdminCapabilities()
    {
        $this->group1->allowAdminsTo(C::VIEW_USER_NAMES);
        $this->group2->allowAdminsTo(C::VIEW_USER_ADDRESS_DATA);
        $this->assertTrue($this->admin->hasCapability(C::VIEW_USER_NAMES));
        $this->assertTrue($this->admin->hasCapability(C::VIEW_USER_ADDRESS_DATA));
    }

    public function testAdminsHaveMemberCapabilities()
    {
        $this->group1->allowMembersTo(C::VIEW_USER_NAMES);
        $this->assertTrue($this->admin->hasCapability(C::VIEW_USER_NAMES));
    }

    public function testMembersHaveNotAdminCapabilities()
    {
        $this->group1->allowAdminsTo(C::MANAGE_USERS);
        $this->assertFalse($this->member->hasCapability(C::MANAGE_USERS));
    }

    public function testCapabilitiesToArray()
    {
        $c = C::VIEW_USER_ADDRESS_DATA;
        $expected = ['VIEW_USER_NAMES', 'VIEW_USER_ADDRESS_DATA'];
        $is = \App\Models\User\Capability::toArray($c);
        $this->assertArraySubset($is, $expected);
    }

    public function testCapabilitiesFromArray()
    {
        $array = ['MANAGE_USERS', 'MANAGE_GROUPS'];
        $expected = C::MANAGE_USERS | C::MANAGE_GROUPS;

        $actual = C::fromArray($array);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Set up a user that is member of two groups and one that is admin
     * of the two groups.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->group1 = \App\Models\User\Group::create(array('name' => 'Test Group 1'));
        $this->group2 = \App\Models\User\Group::create(array('name' => 'Test Group 2'));
        $this->member = factory(User::class, 1)->create();
        $this->admin = factory(User::class, 1)->create();
        $this->member->groups()->saveMany(array($this->group1, $this->group2));
        $this->admin->groups()->saveMany(array($this->group1, $this->group2), [
            ['is_admin' => true],
            ['is_admin' => true],
        ]);
    }

}
