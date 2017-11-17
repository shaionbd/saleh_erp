<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->references('id')->on('items');
            $table->integer('writter_id')->references('id')->on('users');
            $table->string('description');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('word_counts');
            $table->string('chunk');
            $table->integer('process_status')->default('0');
            $table->integer('is_accepted')->default('0');
            $table->string('submission_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
