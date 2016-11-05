<?php

use App\Models\User\Capability;
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
                Capability::VIEW_USER_ADDRESS_DATA,
                Capability::MANAGE_USERS,
            ],
            [
                2,
                'Alle',
                Capability::VIEW_USER_NAMES | Capability::VIEW_GROUPS,
                0b0,
            ],
            [
                3,
                'Gruppenverwaltung',
                Capability::MANAGE_GROUPS,
                Capability::MANAGE_GROUPS,
            ],
        ];
        $keys = ['id', 'name', 'member_capabilities', 'admin_capabilities'];

        \App\Models\User\Group::unguard();
        foreach ($data as $user) {
            \App\Models\User\Group::create(array_combine($keys, $user));
        }
        \App\Models\User\Group::reguard();
    }
}
