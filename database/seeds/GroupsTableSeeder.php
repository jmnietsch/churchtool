<?php

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                1,
                'Mitglieder',
                \App\Capability::VIEW_USER_ADDRESS_DATA,
                \App\Capability::MANAGE_USERS,
            ],
            [2, 'Alle', \App\Capability::VIEW_USER_NAMES, 0b0],
        ];
        $keys = ['id', 'name', 'member_capabilities', 'admin_capabilities'];

        \App\Group::unguard();
        foreach ($data as $user) {
            \App\Group::create(array_combine($keys, $user));
        }
        \App\Group::reguard();
    }
}
