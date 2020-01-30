<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoritesTable extends Migration
{
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('mosque_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');

            $table->foreign('mosque_id')
                ->references('id')
                ->on('mosques')
                ->onUpdate('cascade');

            $table->primary(['user_id', 'mosque_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
}
