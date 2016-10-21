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
        $data = [
            [
                1,
                'josh.s@gmx.de',
                bcrypt('josh'),
                'my nickname',
                'm',
                'Josua',
                'Schmidt',
                \Carbon\Carbon::create(1983, 10, 23),
                true,
            ],
            [
                2,
                'elena.marquardt@gmail.com',
                bcrypt('elena'),
                'my first name',
                'f',
                'Elena',
                'Marquardt',
                \Carbon\Carbon::create(1966, 2, 11),
                true,
            ],
            [
                3,
                'mathis@hoffpost.de',
                bcrypt('secret'),
                'very secret',
                'm',
                'Mathis',
                'Hoffmann',
                \Carbon\Carbon::create(1980, 3, 11),
                true,
            ],
            [
                4,
                'axel87@hotmail.com',
                bcrypt('axe'),
                'hmm',
                'm',
                'Axel',
                'Müller',
                \Carbon\Carbon::create(1987, 5, 17),
                false,
            ],
            [
                5,
                'claudia.mueller@gmail.com',
                bcrypt('mmm'),
                'dont need that',
                'f',
                'Claudia',
                'Müller',
                \Carbon\Carbon::create(1977, 1, 1),
                true,
            ],
        ];
        $keys = [
            'id',
            'email',
            'password',
            'remember_token',
            'sex',
            'first_name',
            'last_name',
            'date_of_birth',
            'active',
        ];

        \App\User::unguard();
        foreach ($data as $user) {
            \App\User::create(array_combine($keys, $user));
        }
        \App\User::reguard();
    }
}
