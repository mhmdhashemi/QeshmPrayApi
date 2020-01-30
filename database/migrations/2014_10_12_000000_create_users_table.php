<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('phone', 11)->unique();
            $table->string('password')->unique();
            $table->bigInteger('fajr_id')->nullable();
            $table->bigInteger('zohr_id')->nullable();
            $table->bigInteger('asr_id')->nullable();
            $table->bigInteger('maghrib_id')->nullable();
            $table->bigInteger('isha_id')->nullable();
            $table->string('api_token', 120)->unique();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
