<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('user_id');
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
            $table->foreign('group_id')
                ->references('id')->on('groups')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->unique(array('user_id', 'group_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('group_user');
    }
}
