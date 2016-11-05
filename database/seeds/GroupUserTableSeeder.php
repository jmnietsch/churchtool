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

        // user id=1 is member of groups 'Mitglieder' and 'Alle'
        $users[0]->groups()->saveMany(
            [$groups[0], $groups[1]],
            [['is_admin' => false], ['is_admin' => false]]
        );

        // user id=2 is member of groups 'Mitglieder' and 'Alle'
        $users[1]->groups()->saveMany(
            [$groups[0], $groups[1]],
            [['is_admin' => false], ['is_admin' => false]]
        );

        // user id=3 is admin of group 'Mitglieder' and member of group 'Alle'
        $users[2]->groups()->saveMany(
            [$groups[0], $groups[1]],
            [['is_admin' => true], ['is_admin' => false]]
        );

        // user id=4 is member of group 'Alle'
        $users[3]->groups()->saveMany(
            [$groups[1]],
            [['is_admin' => false]]
        );

        // user id=5 is not member of any group

        // user id=6 is admin of group 'Gruppenverwaltung' and member of group 'Mitglieder'
        $users[5]->groups()->saveMany(
            [$groups[2], $groups[0]],
            [['is_admin' => true], ['is_admin' => false]]
        );
    }
}
