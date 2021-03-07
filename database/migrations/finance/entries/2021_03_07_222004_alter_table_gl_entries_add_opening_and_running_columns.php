<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableGlEntriesAddOpeningAndRunningColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('GL Entries', function (Blueprint $table) {
            $table->float('Opening_Balance', 24, 2)->after('Balancing_GL_Account_No')->nullable();
            $table->float('Running_Balance', 24, 2)->after('Credit')->nullable();
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
