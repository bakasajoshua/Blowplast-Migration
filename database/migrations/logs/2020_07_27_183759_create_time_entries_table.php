<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('source')->nullable();
            $table->string('destination')->nullable();
            $table->string('Country');
            $table->dateTime('source_start_time')->nullable();
            $table->dateTime('source_end_time')->nullable();
            $table->dateTime('destination_start_time')->nullable();
            $table->dateTime('destination_end_time')->nullable();
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
        Schema::dropIfExists('time_entries');
    }
}
