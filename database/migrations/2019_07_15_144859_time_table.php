<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_table',function(Blueprint $table){
            $table->string("department");
            $table->string("year");
            $table->char("division");
            $table->string("day");
            $table->string("subject");
            $table->string("sdrn");
            $table->time("start_time");
            $table->time("end_time");
            $table->string("batch")->default("ALL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_table');
    }
}
