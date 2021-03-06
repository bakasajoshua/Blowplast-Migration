<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartOfAccountsBreakdownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GL_Accounts_Level_3', function (Blueprint $table) {
            $table->string('Level_3_ID')->primary();
            $table->string('Level_3_Description');
            $table->string('Level_2_ID');

            // $table->foreign('Level_2_ID')->references('Level_2_ID')->on('GL_Accounts_Level_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GL_Accounts_Level_3');
    }
}
