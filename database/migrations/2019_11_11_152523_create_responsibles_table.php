<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponsiblesTable extends Migration
{
    public function up()
    {
        Schema::create('responsibles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('mosque_id');
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');

            $table->foreign('mosque_id')
                ->references('id')
                ->on('mosques')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('responsibles');
    }
}
