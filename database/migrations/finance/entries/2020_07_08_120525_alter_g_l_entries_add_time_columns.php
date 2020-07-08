<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGLEntriesAddTimeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('GL Entries', function (Blueprint $table) {
            $table->string('week')->nullable()->after('Day');
            $table->string('month')->nullable()->after('week');
            $table->tinyInteger('quarter')->nullable()->after('month');
            $table->integer('year')->nullable()->after('quarter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
