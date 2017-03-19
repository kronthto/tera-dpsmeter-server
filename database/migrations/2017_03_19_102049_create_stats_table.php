<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('encounter_unix');
            $table->unsignedInteger('area_id');
            $table->unsignedInteger('boss_id');
            $table->binary('data');
            $table->string('meter_name');
            $table->string('meter_version')->nullable();
            $table->timestamps();

            $table->unique(['encounter_unix', 'area_id', 'boss_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('stats');
    }
}
