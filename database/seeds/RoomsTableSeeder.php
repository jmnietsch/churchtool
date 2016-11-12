<?php

use App\Models\Room\Room;
use Illuminate\Database\Seeder;

class RoomsTableSeeder extends Seeder
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
                'Saal',
            ],
            [
                'Kapelle',
            ],
            [
                'Küche EG',
            ],
            [
                'Kinder 1',
            ],
            [
                'Kinder 2',
            ],
            [
                'Büro',
            ],
        ];

        $keys = ['name'];

        foreach ($data as $room) {
            Room::create(array_combine($keys, $room));
        }
    }
}
