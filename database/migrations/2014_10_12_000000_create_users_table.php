<?php

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
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->dateTime('dob');
            $table->string('avatar', 200)->nullable();
            $table->string('address', 200);
            $table->string('mobile_phone', 45)->nullable();
            $table->string('home_phone', 45)->nullable();
            $table->string('city', 45);
            $table->string('state', 45);
            $table->integer('country');
            $table->string('password', 60);
            $table->string('oauth_token')->nullable();
            $table->dateTime('exp_time_token')->nullable();
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
        Schema::drop('users');
    }
}
