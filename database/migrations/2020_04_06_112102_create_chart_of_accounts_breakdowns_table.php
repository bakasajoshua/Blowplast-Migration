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
        Schema::create('LU_GL_Accounts_Level_3', function (Blueprint $table) {
            $table->string('Account')->primary();
            $table->string('Account_Name');
            $table->string('LU_GL_Accounts_Level_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('LU_GL_Accounts_Level_3');
    }
}
