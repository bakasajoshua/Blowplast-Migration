<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGlaccountsConvertLevelColumnsToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('[GL Accounts]', function (Blueprint $table) {
            $table->string('[GL_Account_Level_2]')->change();
            $table->string('[GL_Account_Level_3]')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('[GL Accounts]', function (Blueprint $table) {
            //
        });
    }
}
