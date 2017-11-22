<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->default('null');
            $table->string('designation')->default('null');
            $table->string('about_me')->default('null');
            $table->string('website')->default('null');
            $table->string('skills')->default('null');
            $table->string('experience')->default('null');
            $table->string('address')->default('null');
            $table->string('password');
            $table->integer('role')->default('4'); /* Role Of Client */
            $table->string('is_available')->default('1');
            $table->string('current_status')->default('null');
            $table->string('supervisor')->default('null');
            $table->string('fb_link')->default('null');
            $table->string('google_plus_link')->default('null');
            $table->string('linkedin_link')->default('null');
            $table->string('twitter_link')->default('null');
            $table->string('github_link')->default('null');
            $table->string('image')->default('default.png');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }

}
