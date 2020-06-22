<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGLAccountsLevel2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('GL_Accounts_Level_2', function (Blueprint $table) {
            $table->string('Company_Code')->nullable();

            // $table->foreign('Level_1_ID')->references('Level_1_ID')->on('GL_Accounts_Level_1');
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
