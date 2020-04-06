<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartOfAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('LU_GL_Accounts_Level_2', function (Blueprint $table) {
<<<<<<< HEAD
            $table->bigIncrements('Chart_of_Account_Group');
=======
            $table->string('Chart_of_Account_Group')->primary();
>>>>>>> bd830bd38760a970bde737c321ca892ab24b6947
            $table->string('Chart_of_Account_Group_Name');
            $table->integer('LU_GL_Accounts_Level_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('LU_GL_Accounts_Level_2');
    }
}
