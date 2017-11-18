<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('writter_id')->references('id')->on('users');
            $table->integer('task_id')->references('id')->on('tasks');
            $table->integer('manager_rivision')->default(0);
            $table->integer('admin_rivision');
            $table->text('admin_revision_description');
            $table->text('manager_revision_description');
            $table->string('submission_date');
            $table->string('re_submission_date');
            $table->integer('is_rivision_onGoing')->default(0);
            $table->integer('is_accepted')->default(0);
            $table->string('file');
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
        Schema::dropIfExists('item_submissions');
    }
}
