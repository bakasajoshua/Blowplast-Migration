<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGLAccountsBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GL Accounts Budget', function (Blueprint $table) {
            $table->string('GL_Account_Budget_No')->primary();
            $table->string('GL_Account_No');
            $table->string('Company_Code', 10);
            $table->string('Budget_Year');
            $table->string('Budget_Month');
            $table->float('Budget_Amount_Excluding_Tax')->nullable();
            $table->float('Budget_Amount_Including_Tax')->nullable();

            // $table->foreign('GL_Account_No')->references('GL_Account_No')->on('GL Accounts');
            // $table->foreign('Company_Code')->references('Company_Code')->on('Companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GL Accounts Budget');
    }
}
