<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMosquesTable extends Migration
{
    public function up()
    {
        Schema::create('mosques', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('city_id');
            $table->string('name', 50);
            $table->string('imam', 50)->nullable();
            $table->string('address', 50)->nullable();
            $table->smallInteger('fajr');
            $table->smallInteger('eq_fajr');
            $table->smallInteger('zohr');
            $table->smallInteger('eq_zohr');
            $table->smallInteger('asr');
            $table->smallInteger('eq_asr');
            $table->smallInteger('maghrib');
            $table->smallInteger('eq_maghrib');
            $table->smallInteger('isha');
            $table->smallInteger('eq_isha');
            $table->integer('favorite_cont')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mosques');
    }
}
