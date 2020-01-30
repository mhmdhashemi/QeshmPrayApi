<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuidsTable extends Migration
{
    public function up()
    {
        Schema::create('guids', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 100);
            $table->text('body');
        });
    }

    public function down()
    {
        Schema::dropIfExists('guids');
    }
}
