<?php

use Illuminate\Database\Seeder;

class GroupUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var \App\User[] $users */
        $users = \App\User::all();
        /** @var \App\Group[] $groups */
        $groups = \App\Group::all();

        $users[0]->groups()->saveMany(
            [$groups[0], $groups[1]],
            [['is_admin' => false], ['is_admin' => false]]
        );
        $users[1]->groups()->saveMany(
            [$groups[0], $groups[1]],
            [['is_admin' => false], ['is_admin' => false]]
        );
        $users[2]->groups()->saveMany(
            [$groups[0], $groups[1]],
            [['is_admin' => true], ['is_admin' => false]]
        );
        $users[3]->groups()->saveMany([$groups[1]], [['is_admin' => false]]);
    }
}
