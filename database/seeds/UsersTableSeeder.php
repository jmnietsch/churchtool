<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    public static $data = '';

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
                'f',
                'Claudia',
                'Müller',
                \Carbon\Carbon::create(1977, 1, 1),
                true,
            ],
            [
                6,
                'bern75@gmx.de',
                bcrypt('derbernd'),
                'm',
                'Bernd',
                'Schleicher',
                \Carbon\Carbon::create(1975, 6, 3),
                true,
            ],
        ];

        // store data in static property
        self::$data = $data;

        $keys = [
            'id',
            'email',
            'password',
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
