<?php

namespace tests\Unit;


use App\Models\Room\Room;
use App\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RoomTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Test creating a new room
     */
    public function testCreate()
    {
        $model = $this->model();

        $this->seeInDatabase(
            'rooms',
            [
                'id' => $model->id,
                'name' => $model->name,
            ]
        );
    }

    /**
     * @param bool $create
     * @return Room
     */
    protected function model($create = true)
    {
        $builder = factory(Room::class);

        return $create ? $builder->create() : $builder->make();
    }

    /**
     * Test deleting a  room
     */
    public function testDelete()
    {
        $model = $this->model();
        $model->delete();

        $this->notSeeInDatabase(
            'rooms',
            [
                'id' => $model->id,
                'name' => $model->name,
            ]
        );
    }

}