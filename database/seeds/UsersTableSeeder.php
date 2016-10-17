<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 50)->create()->each(function (App\User $user) {
            $groups = \App\Group::all();
            $added = new ArrayObject();


        });
    }
}
